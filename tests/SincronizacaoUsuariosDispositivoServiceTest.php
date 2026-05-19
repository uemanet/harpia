<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Models\MapeamentoDispositivo;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\MapeamentoDispositivoRepository;
use Modulos\RH\Services\ControlIdApiClient;
use Modulos\RH\Services\SincronizacaoUsuariosDispositivoService;

class SincronizacaoUsuariosDispositivoServiceTest extends TestCase
{
    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->criarSchemaMinimo();
    }

    public function testSincronizarReatribuiColaboradorParaEvitarDuplicidadeNoMesmoDispositivo(): void
    {
        $dispositivo = $this->criarDispositivo('Entrada');
        $colaborador = $this->criarColaboradorAtivo();

        MapeamentoDispositivo::create([
            'map_dis_id' => $dispositivo->dis_id,
            'map_col_id' => $colaborador->col_id,
            'map_user_id' => '10',
            'map_registration' => (string) $colaborador->col_id,
            'map_nome_dispositivo' => 'Usuario Antigo',
            'map_ativo' => true,
        ]);

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 10, 'registration' => (string) $colaborador->col_id, 'name' => 'Usuario Antigo'],
                ['id' => 20, 'registration' => (string) $colaborador->col_id, 'name' => 'Usuario Novo'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizar($dispositivo);

        $usuarios = collect($resultado['usuarios'])->keyBy('user_id');

        $this->assertNull($usuarios['10']['col_id']);
        $this->assertSame('pendente', $usuarios['10']['status_vinculo']);
        $this->assertSame($colaborador->col_id, $usuarios['20']['col_id']);
        $this->assertSame('vinculado', $usuarios['20']['status_vinculo']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '10',
            'map_col_id' => null,
        ]);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '20',
            'map_col_id' => $colaborador->col_id,
        ]);
    }

    public function testAtualizarUsuarioPropagaFotoParaOutrosDispositivosAtivos(): void
    {
        $origem = $this->criarDispositivo('Entrada');
        $destino = $this->criarDispositivo('Saida', 'saida');

        $apiClient = new FakeControlIdApiClient([
            $origem->dis_id => [
                ['id' => 11, 'registration' => 'ABC', 'name' => 'Alice'],
            ],
            $destino->dis_id => [
                ['id' => 21, 'registration' => 'ABC', 'name' => 'Alice'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $service->atualizarUsuario($origem, '11', ['registration' => 'ABC'], null, 'face-binaria');

        $this->assertCount(2, $apiClient->setUserImageCalls);
        $this->assertSame([
            'device_id' => $origem->dis_id,
            'user_id' => 11,
            'image' => 'face-binaria',
        ], $apiClient->setUserImageCalls[0]);
        $this->assertSame([
            'device_id' => $destino->dis_id,
            'user_id' => 21,
            'image' => 'face-binaria',
        ], $apiClient->setUserImageCalls[1]);
        $this->assertTrue($apiClient->usuario($destino->dis_id, 21)['has_image']);
    }

    public function testSincronizarEntreDispositivosUsaUserIdComoFallbackQuandoRegistrationNaoExiste(): void
    {
        $origem = $this->criarDispositivo('Entrada');
        $destino = $this->criarDispositivo('Saida', 'saida');

        $apiClient = new FakeControlIdApiClient([
            $origem->dis_id => [
                ['id' => 127, 'registration' => '', 'name' => 'Maria Hardware'],
            ],
            $destino->dis_id => [],
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarUsuariosEntreDispositivos($origem, $destino);

        $this->assertSame(1, $resultado['criados']);
        $this->assertSame(0, $resultado['erros']);
        $this->assertCount(1, $apiClient->createUserCalls);
        $this->assertSame('127', $apiClient->createUserCalls[0]['payload']['registration']);
        $this->assertSame('Maria Hardware', $apiClient->createUserCalls[0]['payload']['name']);

        $usuariosDestino = $apiClient->usuariosDoDispositivo($destino->dis_id);
        $this->assertCount(1, $usuariosDestino);
        $this->assertSame('127', (string) $usuariosDestino[0]['registration']);
    }

    public function testSincronizarEntreDispositivosNaoDuplicaUsuarioJaSincronizadoSemRegistration(): void
    {
        $origem = $this->criarDispositivo('Entrada');
        $destino = $this->criarDispositivo('Saida', 'saida');

        $apiClient = new FakeControlIdApiClient([
            $origem->dis_id => [
                ['id' => 127, 'registration' => '', 'name' => 'Maria Hardware'],
            ],
            $destino->dis_id => [
                ['id' => 200, 'registration' => '127', 'name' => 'Maria Hardware'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarUsuariosEntreDispositivos($origem, $destino);

        $this->assertSame(0, $resultado['criados']);
        $this->assertSame(0, $resultado['atualizados']);
        $this->assertSame(1, $resultado['inalterados']);
        $this->assertCount(0, $apiClient->createUserCalls);
        $this->assertCount(1, $apiClient->usuariosDoDispositivo($destino->dis_id));
    }

    private function criarService(FakeControlIdApiClient $apiClient): SincronizacaoUsuariosDispositivoService
    {
        return new SincronizacaoUsuariosDispositivoService(
            $apiClient,
            new ColaboradorRepository(new Colaborador()),
            new MapeamentoDispositivoRepository(new MapeamentoDispositivo())
        );
    }

    private function criarDispositivo(string $nome, string $tipo = 'entrada'): DispositivoAcesso
    {
        $sufixo = strtolower($nome) . '-' . uniqid();

        return DispositivoAcesso::create([
            'dis_nome' => $nome,
            'dis_identificador' => 'IDFACE-' . strtoupper($sufixo),
            'dis_tipo' => $tipo,
            'dis_ip' => '172.16.2.' . random_int(10, 200),
            'dis_modelo' => 'iDFace',
            'dis_token_api' => hash('sha256', $sufixo),
            'dis_status' => 'ativo',
        ]);
    }

    private function criarColaboradorAtivo(): Colaborador
    {
        $pessoaId = DB::table('gra_pessoas')->insertGetId([
            'pes_nome' => 'Pessoa ' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $colaboradorId = DB::table('reh_colaboradores')->insertGetId([
            'col_pes_id' => $pessoaId,
            'col_status' => 'ativo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Colaborador::query()->findOrFail($colaboradorId);
    }

    private function criarSchemaMinimo(): void
    {
        Schema::dropAllTables();

        Schema::create('gra_pessoas', function (Blueprint $table) {
            $table->increments('pes_id');
            $table->string('pes_nome');
            $table->timestamps();
        });

        Schema::create('reh_colaboradores', function (Blueprint $table) {
            $table->increments('col_id');
            $table->unsignedInteger('col_pes_id');
            $table->string('col_status', 20);
            $table->timestamps();

            $table->foreign('col_pes_id')->references('pes_id')->on('gra_pessoas');
        });

        Schema::create('reh_dispositivos_acesso', function (Blueprint $table) {
            $table->increments('dis_id');
            $table->string('dis_nome');
            $table->string('dis_identificador')->unique();
            $table->string('dis_tipo', 20);
            $table->string('dis_ip', 45)->nullable();
            $table->string('dis_modelo')->nullable();
            $table->string('dis_token_api', 64)->unique();
            $table->string('dis_status', 20)->default('ativo');
            $table->timestamps();
        });

        Schema::create('reh_mapeamento_dispositivo', function (Blueprint $table) {
            $table->increments('map_id');
            $table->unsignedInteger('map_dis_id');
            $table->unsignedInteger('map_col_id')->nullable();
            $table->string('map_user_id', 20);
            $table->string('map_registration', 50);
            $table->string('map_nome_dispositivo')->nullable();
            $table->boolean('map_ativo')->default(true);
            $table->timestamps();

            $table->foreign('map_dis_id')->references('dis_id')->on('reh_dispositivos_acesso');
            $table->foreign('map_col_id')->references('col_id')->on('reh_colaboradores');
            $table->unique(['map_dis_id', 'map_user_id']);
            $table->unique(['map_dis_id', 'map_col_id']);
        });
    }
}

class FakeControlIdApiClient extends ControlIdApiClient
{
    public array $createUserCalls = [];
    public array $modifyUserCalls = [];
    public array $setUserImageCalls = [];

    private array $usuariosPorDispositivo;
    private array $imagensPorDispositivoUsuario;
    private array $gruposPorDispositivo = [];
    private array $gruposUsuarioPorDispositivo = [];
    private int $proximoUserId = 1000;

    public function __construct(array $usuariosPorDispositivo = [], array $imagensPorDispositivoUsuario = [])
    {
        $this->usuariosPorDispositivo = [];
        $this->imagensPorDispositivoUsuario = $imagensPorDispositivoUsuario;

        foreach ($usuariosPorDispositivo as $dispositivoId => $usuarios) {
            $this->usuariosPorDispositivo[$dispositivoId] = [];

            foreach ($usuarios as $usuario) {
                $id = (int) $usuario['id'];
                $this->usuariosPorDispositivo[$dispositivoId][$id] = array_merge([
                    'id' => $id,
                    'registration' => '',
                    'name' => '',
                    'has_image' => false,
                ], $usuario);
                $this->proximoUserId = max($this->proximoUserId, $id + 1);
            }
        }
    }

    public function loadObjects(DispositivoAcesso $dispositivo, string $object, array $filters = [], ?int $loadAfterId = null): array
    {
        if ($object === 'users') {
            return [
                'users' => array_values($this->usuariosPorDispositivo[$dispositivo->dis_id] ?? []),
            ];
        }

        if ($object === 'groups') {
            return [
                'groups' => $this->gruposPorDispositivo[$dispositivo->dis_id] ?? [[
                    'id' => 1,
                    'name' => 'Padrao',
                ]],
            ];
        }

        if ($object === 'user_groups') {
            $vinculos = $this->gruposUsuarioPorDispositivo[$dispositivo->dis_id] ?? [];
            $userId = $filters['where']['user_groups']['user_id'] ?? null;

            if ($userId !== null) {
                $vinculos = array_values(array_filter($vinculos, function (array $vinculo) use ($userId) {
                    return (int) $vinculo['user_id'] === (int) $userId;
                }));
            }

            return ['user_groups' => $vinculos];
        }

        return [$object => []];
    }

    public function createUser(DispositivoAcesso $dispositivo, array $userData): array
    {
        $this->createUserCalls[] = [
            'device_id' => $dispositivo->dis_id,
            'payload' => $userData,
        ];

        $id = $this->proximoUserId++;
        $this->usuariosPorDispositivo[$dispositivo->dis_id][$id] = [
            'id' => $id,
            'registration' => (string) ($userData['registration'] ?? ''),
            'name' => (string) ($userData['name'] ?? ''),
            'has_image' => false,
        ];

        return ['id' => $id];
    }

    public function createUserGroup(DispositivoAcesso $dispositivo, int $userId, int $groupId): array
    {
        $this->gruposUsuarioPorDispositivo[$dispositivo->dis_id][] = [
            'user_id' => $userId,
            'group_id' => $groupId,
        ];

        return ['changes' => 1];
    }

    public function modifyUser(DispositivoAcesso $dispositivo, int $userId, array $userData): array
    {
        $this->modifyUserCalls[] = [
            'device_id' => $dispositivo->dis_id,
            'user_id' => $userId,
            'payload' => $userData,
        ];

        if (isset($this->usuariosPorDispositivo[$dispositivo->dis_id][$userId])) {
            $usuario = &$this->usuariosPorDispositivo[$dispositivo->dis_id][$userId];

            if (array_key_exists('name', $userData)) {
                $usuario['name'] = (string) $userData['name'];
            }

            if (array_key_exists('registration', $userData)) {
                $usuario['registration'] = (string) $userData['registration'];
            }
        }

        return ['changes' => 1];
    }

    public function setUserImage(
        DispositivoAcesso $dispositivo,
        int $userId,
        string $imageBinary,
        ?int $timestamp = null,
        bool $match = false
    ): array {
        $this->setUserImageCalls[] = [
            'device_id' => $dispositivo->dis_id,
            'user_id' => $userId,
            'image' => $imageBinary,
        ];

        $this->imagensPorDispositivoUsuario[$dispositivo->dis_id][$userId] = $imageBinary;

        if (isset($this->usuariosPorDispositivo[$dispositivo->dis_id][$userId])) {
            $this->usuariosPorDispositivo[$dispositivo->dis_id][$userId]['has_image'] = true;
            $this->usuariosPorDispositivo[$dispositivo->dis_id][$userId]['image_timestamp'] = $timestamp ?? time();
        }

        return ['success' => true];
    }

    public function getUserImage(DispositivoAcesso $dispositivo, int $userId): string
    {
        return $this->imagensPorDispositivoUsuario[$dispositivo->dis_id][$userId] ?? ('image-' . $userId);
    }

    public function usuario(int $dispositivoId, int $userId): array
    {
        return $this->usuariosPorDispositivo[$dispositivoId][$userId];
    }

    public function usuariosDoDispositivo(int $dispositivoId): array
    {
        return array_values($this->usuariosPorDispositivo[$dispositivoId] ?? []);
    }
}
