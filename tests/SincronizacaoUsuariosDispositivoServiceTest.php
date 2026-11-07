<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modulos\Geral\Models\Anexo;
use Modulos\Geral\Repositories\AnexoRepository;
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
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $app['config']->set('database.connections.sqlite_rh_sync_test', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('database.default', 'sqlite_rh_sync_test');

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

    public function testAtualizarUsuarioEmTodosDispositivosPropagaFotoDoColaborador(): void
    {
        $origem = $this->criarDispositivo('Entrada');
        $destino = $this->criarDispositivo('Saida', 'saida');
        $colaborador = $this->criarColaboradorAtivo();

        $apiClient = new FakeControlIdApiClient([
            $origem->dis_id => [
                ['id' => 11, 'registration' => (string) $colaborador->col_id, 'name' => 'Alice'],
            ],
            $destino->dis_id => [
                ['id' => 21, 'registration' => (string) $colaborador->col_id, 'name' => 'Alice'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $service->atualizarUsuarioEmTodosDispositivos($origem, '11', $colaborador->col_id);

        $this->assertCount(2, $apiClient->setUserImageCalls);
        $this->assertSame([
            'device_id' => $origem->dis_id,
            'user_id' => 11,
            'image' => $this->conteudoFotoDoColaborador($colaborador),
        ], $apiClient->setUserImageCalls[0]);
        $this->assertSame([
            'device_id' => $destino->dis_id,
            'user_id' => 21,
            'image' => $this->conteudoFotoDoColaborador($colaborador),
        ], $apiClient->setUserImageCalls[1]);
        $this->assertTrue($apiClient->usuario($destino->dis_id, 21)['has_image']);
    }

    public function testGarantirUsuarioEmDispositivosRetornaErroQuandoColaboradorNaoTemFoto(): void
    {
        $dispositivo = $this->criarDispositivo('Entrada');
        $colaborador = $this->criarColaboradorAtivo(false);

        $service = $this->criarService(new FakeControlIdApiClient());
        $resultado = $service->garantirUsuarioEmDispositivos([$dispositivo], $colaborador->col_id);

        $this->assertSame(1, $resultado['total']);
        $this->assertSame(0, $resultado['criados']);
        $this->assertSame(0, $resultado['atualizados']);
        $this->assertCount(1, $resultado['erros']);
        $this->assertSame('O colaborador selecionado não possui foto facial cadastrada.', $resultado['erros'][0]['mensagem']);
    }

    public function testAtualizarUsuarioEmTodosDispositivosUsaFotoManualQuandoInformada(): void
    {
        $origem = $this->criarDispositivo('Entrada');
        $destino = $this->criarDispositivo('Saida', 'saida');
        $colaborador = $this->criarColaboradorAtivo();

        $apiClient = new FakeControlIdApiClient([
            $origem->dis_id => [
                ['id' => 11, 'registration' => (string) $colaborador->col_id, 'name' => 'Alice'],
            ],
            $destino->dis_id => [
                ['id' => 21, 'registration' => (string) $colaborador->col_id, 'name' => 'Alice'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $service->atualizarUsuarioEmTodosDispositivos($origem, '11', $colaborador->col_id, 'foto-manual-binaria');

        $this->assertCount(2, $apiClient->setUserImageCalls);
        $this->assertSame('foto-manual-binaria', $apiClient->setUserImageCalls[0]['image']);
        $this->assertSame('foto-manual-binaria', $apiClient->setUserImageCalls[1]['image']);
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

    public function testVincularUsuarioAtualizaRegistrationENomeNoDispositivo(): void
    {
        $dispositivo = $this->criarDispositivo('Entrada');
        $colaborador = $this->criarColaboradorAtivo();

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 55, 'registration' => '', 'name' => 'Usuario Manual'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $service->vincularUsuario($dispositivo, '55', $colaborador->col_id);

        $this->assertCount(1, $apiClient->modifyUserCalls);
        $this->assertSame([
            'device_id' => $dispositivo->dis_id,
            'user_id' => 55,
            'payload' => [
                'name' => $colaborador->pessoa->pes_nome,
                'registration' => (string) $colaborador->col_id,
            ],
        ], $apiClient->modifyUserCalls[0]);

        $usuarioAtualizado = $apiClient->usuario($dispositivo->dis_id, 55);

        $this->assertSame((string) $colaborador->col_id, (string) $usuarioAtualizado['registration']);
        $this->assertSame($colaborador->pessoa->pes_nome, $usuarioAtualizado['name']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '55',
            'map_col_id' => $colaborador->col_id,
            'map_registration' => (string) $colaborador->col_id,
            'map_nome_dispositivo' => $colaborador->pessoa->pes_nome,
        ]);
    }

    public function testSincronizarColaboradoresEmDispositivosContinuaAposFalhaFacialEListaMotivosAgrupados(): void
    {
        $entrada = $this->criarDispositivo('iDFace Entrada', 'entrada');
        $saida = $this->criarDispositivo('iDFace Saida', 'saida');

        $ana = $this->criarColaboradorAtivo(true, 'Ana Silva');
        $bruno = $this->criarColaboradorAtivo(true, 'Bruno Costa');
        $carla = $this->criarColaboradorAtivo(true, 'Carla Mendes');
        $this->criarColaboradorAtivo(false, 'Diego Sem Foto');

        $fotoRejeitada = $this->conteudoFotoDoColaborador($bruno);

        $apiClient = new FakeControlIdApiClient();
        $apiClient->rejeitarImagem($fotoRejeitada, 'Face too close to image borders');

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarColaboradoresEmDispositivos([$entrada, $saida]);

        $this->assertSame([], $resultado['erros']);
        $this->assertSame([
            'dispositivos' => 2,
            'criados' => 4,
            'atualizados' => 0,
            'fotos_sincronizadas' => 4,
            'pendentes_foto' => 2,
            'inalterados' => 0,
            'falhas' => 2,
        ], $resultado['resumo']);

        $this->assertSame([[
            'col_id' => $bruno->col_id,
            'nome' => 'Bruno Costa',
            'registration' => (string) $bruno->col_id,
            'colaborador' => sprintf('Bruno Costa (matrícula %d)', $bruno->col_id),
            'dispositivos' => ['iDFace Entrada', 'iDFace Saida'],
            'mensagem' => 'O dispositivo rejeitou a foto facial: Rosto muito próximo das bordas da imagem',
        ]], $resultado['falhas_colaboradores']);

        foreach ([$entrada, $saida] as $dispositivo) {
            $usuariosPorReg = collect($apiClient->usuariosDoDispositivo($dispositivo->dis_id))->keyBy('registration');

            $this->assertTrue($usuariosPorReg[(string) $ana->col_id]['has_image']);
            $this->assertTrue($usuariosPorReg[(string) $carla->col_id]['has_image']);
            $this->assertFalse($usuariosPorReg[(string) $bruno->col_id]['has_image']);

            $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
                'map_dis_id' => $dispositivo->dis_id,
                'map_col_id' => $ana->col_id,
            ]);
            $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
                'map_dis_id' => $dispositivo->dis_id,
                'map_col_id' => $carla->col_id,
            ]);
        }
    }

    public function testSincronizarColaboradoresEmDispositivosContinuaQuandoArquivoDeFotoEstaAusente(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Entrada');
        $ausente = $this->criarColaboradorAtivo(true, 'Marcos Arquivo Ausente');
        $valido = $this->criarColaboradorAtivo(true, 'Paula Foto Valida');

        $caminhoAusente = app(AnexoRepository::class)->obterCaminhoAnexo($ausente->col_foto_anx_id);
        @unlink($caminhoAusente);

        $apiClient = new FakeControlIdApiClient();
        $service = $this->criarService($apiClient);

        $resultado = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);

        $this->assertSame(1, $resultado['resumo']['criados']);
        $this->assertSame(1, $resultado['resumo']['fotos_sincronizadas']);
        $this->assertSame(1, $resultado['resumo']['falhas']);
        $this->assertCount(1, $resultado['falhas_colaboradores']);
        $this->assertSame($ausente->col_id, $resultado['falhas_colaboradores'][0]['col_id']);
        $this->assertSame('Marcos Arquivo Ausente', $resultado['falhas_colaboradores'][0]['nome']);
        $this->assertSame('Nao foi possivel ler o arquivo do anexo informado.', $resultado['falhas_colaboradores'][0]['mensagem']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_col_id' => $valido->col_id,
        ]);
    }

    public function testSincronizarColaboradoresEmDispositivosIsolaDispositivoInacessivel(): void
    {
        $offline = $this->criarDispositivo('iDFace Offline');
        $online = $this->criarDispositivo('iDFace Online', 'saida');
        $colaborador = $this->criarColaboradorAtivo(true, 'Renata Online');

        $apiClient = new FakeControlIdApiClient();
        $apiClient->marcarDispositivoInacessivel($offline->dis_id);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarColaboradoresEmDispositivos([$offline, $online]);

        $this->assertSame(1, $resultado['resumo']['dispositivos']);
        $this->assertSame(1, $resultado['resumo']['criados']);
        $this->assertSame(0, $resultado['resumo']['falhas']);
        $this->assertCount(1, $resultado['erros']);
        $this->assertSame('iDFace Offline', $resultado['erros'][0]['dispositivo']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $online->dis_id,
            'map_col_id' => $colaborador->col_id,
        ]);
    }

    public function testUsuariosJaCompativeisNaoReenviamFotoNemModificamHardwareAteFotoMudar(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Portaria');
        $ana = $this->criarColaboradorAtivo(true, 'Ana Compativel');
        $bruno = $this->criarColaboradorAtivo(true, 'Bruno Compativel');

        $apiClient = new FakeControlIdApiClient();
        $service = $this->criarService($apiClient);

        $primeira = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);
        $this->assertSame(2, $primeira['resumo']['criados']);
        $this->assertSame(2, $primeira['resumo']['fotos_sincronizadas']);
        $this->assertSame(0, $primeira['resumo']['inalterados']);

        $apiClient->createUserCalls = [];
        $apiClient->modifyUserCalls = [];
        $apiClient->setUserImageCalls = [];
        $apiClient->loadObjectsCalls = [];

        $segunda = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);
        $this->assertSame(0, $segunda['resumo']['criados']);
        $this->assertSame(0, $segunda['resumo']['atualizados']);
        $this->assertSame(0, $segunda['resumo']['fotos_sincronizadas']);
        $this->assertSame(2, $segunda['resumo']['inalterados']);
        $this->assertCount(0, $apiClient->createUserCalls);
        $this->assertCount(0, $apiClient->modifyUserCalls);
        $this->assertCount(0, $apiClient->setUserImageCalls);

        // Groups e user_groups carregados apenas 1 vez por dispositivo (sem N+1 HTTP)
        $chamadasPorObjeto = array_count_values(array_column($apiClient->loadObjectsCalls, 'object'));
        $this->assertSame(1, $chamadasPorObjeto['groups'] ?? 0);
        $this->assertSame(1, $chamadasPorObjeto['user_groups'] ?? 0);
        $this->assertSame(1, $chamadasPorObjeto['users'] ?? 0);

        // Se a foto facial for atualizada no Harpia depois do image_timestamp do aparelho, reenviar apenas ela
        DB::table('gra_anexos')
            ->where('anx_id', $ana->col_foto_anx_id)
            ->update(['updated_at' => now()->addMinutes(10)]);

        $terceira = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);
        $this->assertSame(1, $terceira['resumo']['atualizados']);
        $this->assertSame(1, $terceira['resumo']['fotos_sincronizadas']);
        $this->assertSame(1, $terceira['resumo']['inalterados']);
        $this->assertCount(1, $apiClient->setUserImageCalls);
    }

    public function testVinculaUsuarioExistenteNoHardwarePorNomeMesmoSemFotoNoHarpiaEPreservaFotoDoAparelho(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Recepção');
        $joao = $this->criarColaboradorAtivo(false, 'João da Silva');

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [[
                'id' => 77,
                'registration' => '',
                'name' => 'JOAO DA SILVA',
                'has_image' => true,
                'image_timestamp' => 1700000000,
            ]],
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);

        $this->assertSame(0, $resultado['resumo']['criados']);
        $this->assertSame(1, $resultado['resumo']['atualizados']);
        $this->assertSame(0, $resultado['resumo']['pendentes_foto']);
        $this->assertCount(0, $apiClient->createUserCalls);
        $this->assertCount(0, $apiClient->setUserImageCalls);
        $this->assertCount(1, $apiClient->modifyUserCalls);

        $usuarioHardware = $apiClient->usuario($dispositivo->dis_id, 77);
        $this->assertSame((string) $joao->col_id, $usuarioHardware['registration']);
        $this->assertSame('João da Silva', $usuarioHardware['name']);
        $this->assertTrue($usuarioHardware['has_image']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '77',
            'map_col_id' => $joao->col_id,
            'map_ativo' => 1,
        ]);
    }

    public function testVinculaUsuariosExistentesPorZerosAEsquerdaEMatriculaUniversidadeOuCatraca(): void
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            $table->string('col_matricula_universidade', 50)->nullable();
            $table->string('col_codigo_catraca', 50)->nullable();
        });

        $dispositivo = $this->criarDispositivo('iDFace Bloco A');
        $colZeros = $this->criarColaboradorAtivo(true, 'Helena Zeros');
        $colMatricula = $this->criarColaboradorAtivo(true, 'Igor Matricula');
        $colCatraca = $this->criarColaboradorAtivo(false, 'Julia Catraca');

        DB::table('reh_colaboradores')->where('col_id', $colMatricula->col_id)->update([
            'col_matricula_universidade' => 'MAT-2026-009',
        ]);
        DB::table('reh_colaboradores')->where('col_id', $colCatraca->col_id)->update([
            'col_codigo_catraca' => 'CAT-8841',
        ]);

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 81, 'registration' => '000' . $colZeros->col_id, 'name' => 'Nome Antigo 1', 'has_image' => true, 'image_timestamp' => time()],
                ['id' => 82, 'registration' => 'MAT-2026-009', 'name' => 'Nome Antigo 2', 'has_image' => true, 'image_timestamp' => time()],
                ['id' => 83, 'registration' => 'CAT-8841', 'name' => 'Nome Antigo 3', 'has_image' => true, 'image_timestamp' => time()],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);

        $this->assertSame(0, $resultado['resumo']['criados']);
        $this->assertSame(3, $resultado['resumo']['atualizados']);
        $this->assertCount(0, $apiClient->createUserCalls);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '81',
            'map_col_id' => $colZeros->col_id,
        ]);
        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '82',
            'map_col_id' => $colMatricula->col_id,
        ]);
        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '83',
            'map_col_id' => $colCatraca->col_id,
        ]);
    }

    public function testPersisteMapeamentoImediatamenteMesmoQuandoFotoERejeitadaOuLotePausaNoMeio(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Lote');
        $col1 = $this->criarColaboradorAtivo(true, '01 Colaborador Foto Ruim');
        $col2 = $this->criarColaboradorAtivo(true, '02 Colaborador Foto Boa');
        $col3 = $this->criarColaboradorAtivo(true, '03 Colaborador Proximo Lote');

        $apiClient = new FakeControlIdApiClient();
        $apiClient->rejeitarImagem($this->conteudoFotoDoColaborador($col1), 'Face not detected');

        $service = $this->criarService($apiClient);

        $fatia1 = $service->sincronizarLoteColaboradoresNoDispositivo($dispositivo, 0, 2, 60, 30.0);

        $this->assertFalse($fatia1['concluido']);
        $this->assertSame(2, $fatia1['proximo_offset']);
        $this->assertSame(1, $fatia1['criados']);
        $this->assertCount(1, $fatia1['falhas']);

        // Mesmo antes do lote final e mesmo com a foto do col1 rejeitada, ambos já aparecem vinculados na consulta!
        $consultaParcial = $service->consultar($dispositivo);
        $this->assertSame(2, $consultaParcial['resumo']['total']);
        $this->assertSame(2, $consultaParcial['resumo']['vinculados']);
        $this->assertSame(0, $consultaParcial['resumo']['pendentes']);

        $fatia2 = $service->sincronizarLoteColaboradoresNoDispositivo($dispositivo, $fatia1['proximo_offset'], 2, 60, 30.0);

        $this->assertTrue($fatia2['concluido']);
        $this->assertSame(3, $fatia2['proximo_offset']);
        $this->assertSame(1, $fatia2['criados']);

        $consultaFinal = $service->consultar($dispositivo);
        $this->assertSame(3, $consultaFinal['resumo']['vinculados']);
        $this->assertSame(0, $consultaFinal['resumo']['pendentes']);
    }

    public function testMapeamentoObsoletoDeUsuarioRemovidoNoHardwareNaoBloqueiaVinculacao(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Catraca');
        $colaborador = $this->criarColaboradorAtivo(true, 'Lucas Recadastrado');

        // Mapeamento antigo apontando para user_id=999 que já foi excluído diretamente no aparelho
        MapeamentoDispositivo::create([
            'map_dis_id' => $dispositivo->dis_id,
            'map_col_id' => $colaborador->col_id,
            'map_user_id' => '999',
            'map_registration' => (string) $colaborador->col_id,
            'map_nome_dispositivo' => 'Lucas Antigo',
            'map_ativo' => true,
        ]);

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 44, 'registration' => '', 'name' => 'Lucas Recadastrado'],
            ],
        ]);

        $service = $this->criarService($apiClient);
        $service->vincularUsuario($dispositivo, '44', $colaborador->col_id);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '999',
            'map_col_id' => null,
            'map_ativo' => 0,
        ]);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '44',
            'map_col_id' => $colaborador->col_id,
            'map_ativo' => 1,
        ]);
    }

    public function testControllerPostSincronizarTodosEmLoteDivideEmFatiasGravaFlashEBloqueiaOutroUsuario(): void
    {
        $entrada = $this->criarDispositivo('iDFace Entrada Lote', 'entrada');
        $saida = $this->criarDispositivo('iDFace Saida Lote', 'saida');

        $colaboradoresValidos = [];
        for ($i = 1; $i <= 6; $i++) {
            $colaboradoresValidos[] = $this->criarColaboradorAtivo(true, sprintf('Colaborador Valido %02d', $i));
        }
        $rejeitado = $this->criarColaboradorAtivo(true, 'Colaborador Foto Rejeitada');

        $apiClient = new FakeControlIdApiClient();
        $apiClient->rejeitarImagem($this->conteudoFotoDoColaborador($rejeitado), 'No face detected');
        $this->app->instance(ControlIdApiClient::class, $apiClient);

        $controller = $this->app->make(\Modulos\RH\Http\Controllers\DispositivoUsuariosController::class);

        $this->be(new \Illuminate\Auth\GenericUser(['id' => 10, 'usr_id' => 10]));

        $reqIniciar = \Illuminate\Http\Request::create('/rh/dispositivo-usuarios/sincronizar-todos', 'POST', [
            'dis_id' => $entrada->dis_id,
            'sincronizar_todos_dispositivos' => '1',
            'acao_lote' => 'iniciar',
        ]);
        $reqIniciar->headers->set('Accept', 'application/json');

        $respIniciar = $controller->postSincronizarTodos($reqIniciar);
        $this->assertSame(200, $respIniciar->getStatusCode());
        $dadosIniciar = $respIniciar->getData(true);

        $this->assertFalse($dadosIniciar['concluido']);
        $this->assertSame(2, $dadosIniciar['total_dispositivos']);
        $this->assertSame(7, $dadosIniciar['total_colaboradores']);
        $token = $dadosIniciar['token'];

        // Outro usuário autenticado não pode consumir o token desta sessão
        $this->be(new \Illuminate\Auth\GenericUser(['id' => 99, 'usr_id' => 99]));
        $reqOutroUsuario = \Illuminate\Http\Request::create('/rh/dispositivo-usuarios/sincronizar-todos', 'POST', [
            'dis_id' => $entrada->dis_id,
            'acao_lote' => 'processar',
            'token' => $token,
        ]);
        $reqOutroUsuario->headers->set('Accept', 'application/json');
        $respOutroUsuario = $controller->postSincronizarTodos($reqOutroUsuario);
        $this->assertSame(403, $respOutroUsuario->getStatusCode());

        // Usuário original processa todas as fatias até concluir
        $this->be(new \Illuminate\Auth\GenericUser(['id' => 10, 'usr_id' => 10]));
        $chamadasProcessar = 0;
        $dadosEtapa = $dadosIniciar;

        while (!$dadosEtapa['concluido'] && $chamadasProcessar < 10) {
            $chamadasProcessar++;
            $reqProcessar = \Illuminate\Http\Request::create('/rh/dispositivo-usuarios/sincronizar-todos', 'POST', [
                'dis_id' => $entrada->dis_id,
                'acao_lote' => 'processar',
                'token' => $token,
            ]);
            $reqProcessar->headers->set('Accept', 'application/json');

            $respProcessar = $controller->postSincronizarTodos($reqProcessar);
            $this->assertSame(200, $respProcessar->getStatusCode());
            $dadosEtapa = $respProcessar->getData(true);
        }

        // 7 colaboradores com maxEscritas=5 por fatia => 2 fatias por dispositivo * 2 dispositivos = 4 chamadas
        $this->assertSame(4, $chamadasProcessar);
        $this->assertTrue($dadosEtapa['concluido']);
        $this->assertSame(100, $dadosEtapa['progresso_percentual']);
        $this->assertSame(2, $dadosEtapa['resumo']['dispositivos']);
        $this->assertSame(12, $dadosEtapa['resumo']['criados']);
        $this->assertSame(2, $dadosEtapa['resumo']['falhas']);
        $this->assertStringContainsString('dis_id=' . $entrada->dis_id, $dadosEtapa['redirect_url']);

        $falhasSessao = session('falhas_sincronizacao');
        $this->assertIsArray($falhasSessao);
        $this->assertCount(1, $falhasSessao);
        $this->assertSame($rejeitado->col_id, $falhasSessao[0]['col_id']);

        foreach ($colaboradoresValidos as $col) {
            $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
                'map_dis_id' => $entrada->dis_id,
                'map_col_id' => $col->col_id,
                'map_ativo' => 1,
            ]);
            $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
                'map_dis_id' => $saida->dis_id,
                'map_col_id' => $col->col_id,
                'map_ativo' => 1,
            ]);
        }
    }

    public function testComandoArtisanSincronizarMapeamentoDispositivoVinculaUsuariosReais(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Comando');
        $colaborador = $this->criarColaboradorAtivo(true, 'Camila Comando');

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 33, 'registration' => (string) $colaborador->col_id, 'name' => 'Camila Comando'],
            ],
        ]);
        $this->app->instance(ControlIdApiClient::class, $apiClient);

        $exitCode = \Illuminate\Support\Facades\Artisan::call('ponto:sincronizar-mapeamento', [
            'dis_id' => $dispositivo->dis_id,
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '33',
            'map_col_id' => $colaborador->col_id,
            'map_ativo' => 1,
        ]);
    }

    public function testRemoveClonesDuplicadosExistentesNoDispositivoMantendoRegistroVinculadoComFoto(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Portaria Producao');
        $thamires = $this->criarColaboradorAtivo(true, 'Thamires Vitoria de Oliveira Leão');
        $rodrigo = $this->criarColaboradorAtivo(true, 'RODRIGO EDUARDO COELHO');

        // Reproduz o cenário exato de produção:
        // - Thamires com 4 registros no hardware: 461, 516, 537 (Pendente, Foto OK) e 540 (Vinculado, Foto OK)
        // - Rodrigo com 3 registros Sem foto: 450, 505 (Pendente) e 526 (Vinculado), cuja foto é rejeitada pelo aparelho
        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 450, 'registration' => (string) $rodrigo->col_id, 'name' => 'RODRIGO EDUARDO COELHO', 'has_image' => false],
                ['id' => 461, 'registration' => (string) $thamires->col_id, 'name' => 'Thamires Vitoria de Oliveira Leão', 'has_image' => true, 'image_timestamp' => time()],
                ['id' => 505, 'registration' => (string) $rodrigo->col_id, 'name' => 'RODRIGO EDUARDO COELHO', 'has_image' => false],
                ['id' => 516, 'registration' => (string) $thamires->col_id, 'name' => 'Thamires Vitoria de Oliveira Leão', 'has_image' => true, 'image_timestamp' => time()],
                ['id' => 526, 'registration' => (string) $rodrigo->col_id, 'name' => 'RODRIGO EDUARDO COELHO', 'has_image' => false],
                ['id' => 537, 'registration' => (string) $thamires->col_id, 'name' => 'Thamires Vitoria de Oliveira Leão', 'has_image' => true, 'image_timestamp' => time()],
                ['id' => 540, 'registration' => (string) $thamires->col_id, 'name' => 'Thamires Vitoria de Oliveira Leão', 'has_image' => true, 'image_timestamp' => time()],
            ],
        ]);
        $apiClient->rejeitarImagem($this->conteudoFotoDoColaborador($rodrigo), 'Face not detected');

        foreach ([450, 505] as $uidCloneRodrigo) {
            MapeamentoDispositivo::create([
                'map_dis_id' => $dispositivo->dis_id,
                'map_col_id' => null,
                'map_user_id' => (string) $uidCloneRodrigo,
                'map_registration' => (string) $rodrigo->col_id,
                'map_nome_dispositivo' => 'RODRIGO EDUARDO COELHO',
                'map_ativo' => true,
            ]);
        }
        MapeamentoDispositivo::create([
            'map_dis_id' => $dispositivo->dis_id,
            'map_col_id' => $rodrigo->col_id,
            'map_user_id' => '526',
            'map_registration' => (string) $rodrigo->col_id,
            'map_nome_dispositivo' => 'RODRIGO EDUARDO COELHO',
            'map_ativo' => true,
        ]);

        foreach ([461, 516, 537] as $uidCloneThamires) {
            MapeamentoDispositivo::create([
                'map_dis_id' => $dispositivo->dis_id,
                'map_col_id' => null,
                'map_user_id' => (string) $uidCloneThamires,
                'map_registration' => (string) $thamires->col_id,
                'map_nome_dispositivo' => 'Thamires Vitoria de Oliveira Leão',
                'map_ativo' => true,
            ]);
        }
        MapeamentoDispositivo::create([
            'map_dis_id' => $dispositivo->dis_id,
            'map_col_id' => $thamires->col_id,
            'map_user_id' => '540',
            'map_registration' => (string) $thamires->col_id,
            'map_nome_dispositivo' => 'Thamires Vitoria de Oliveira Leão',
            'map_ativo' => true,
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);

        // 3 clones de Thamires (461, 516, 537) + 2 clones de Rodrigo (450, 505) removidos
        $this->assertSame(5, $resultado['duplicados_removidos']);
        $this->assertCount(0, $apiClient->createUserCalls);

        $idsDestruidos = array_map(fn (array $call) => $call['user_id'], $apiClient->destroyUserCalls);
        sort($idsDestruidos);
        $this->assertSame([450, 461, 505, 516, 537], $idsDestruidos);

        // No hardware sobram apenas os dois registros canônicos (526 e 540), zero Pendentes
        $consultaPosLimpeza = $service->consultar($dispositivo);
        $this->assertSame(2, $consultaPosLimpeza['resumo']['total']);
        $this->assertSame(2, $consultaPosLimpeza['resumo']['vinculados']);
        $this->assertSame(0, $consultaPosLimpeza['resumo']['pendentes']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '540',
            'map_col_id' => $thamires->col_id,
            'map_ativo' => 1,
        ]);
        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '526',
            'map_col_id' => $rodrigo->col_id,
            'map_ativo' => 1,
        ]);

        foreach (['450', '461', '505', '516', '537'] as $cloneRemovido) {
            $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
                'map_dis_id' => $dispositivo->dis_id,
                'map_user_id' => $cloneRemovido,
                'map_col_id' => null,
                'map_ativo' => 0,
            ]);
        }
    }

    public function testNaoDuplicaQuandoUsuarioFoiCriadoNoHardwareAposSnapshotInicialOuPendenteEmCriarUsuario(): void
    {
        $dispositivo = $this->criarDispositivo('iDFace Concorrencia');
        $kaia = $this->criarColaboradorAtivo(true, 'KAIA JANAINA DE SOUSA BARROS');
        $katia = $this->criarColaboradorAtivo(true, 'KÁTIA ALMEIDA FONSÊCA');

        // Cenário A: No snapshot inicial o dispositivo parece não ter Kaia, mas um processo externo
        // acabou de criar o ID 409 no hardware antes do createUser desta execução.
        // Cenário B: Kátia já existe no hardware (ID 410) como Pendente (sem mapeamento em reh_mapeamento_dispositivo).
        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [
                ['id' => 410, 'registration' => (string) $katia->col_id, 'name' => 'KÁTIA ALMEIDA FONSÊCA', 'has_image' => true, 'image_timestamp' => time()],
            ],
        ]);
        $apiClient->injetarUsuarioConcorrenteAoConsultarRegistration($dispositivo->dis_id, (string) $kaia->col_id, [
            'id' => 409,
            'registration' => (string) $kaia->col_id,
            'name' => 'KAIA JANAINA DE SOUSA BARROS',
            'has_image' => true,
            'image_timestamp' => time(),
        ]);

        $service = $this->criarService($apiClient);

        // criarUsuario para Kátia deve reaproveitar o ID 410 existente no hardware em vez de duplicar
        $usuarioKatia = $service->criarUsuario($dispositivo, $katia->col_id);
        $this->assertSame('410', (string) $usuarioKatia['user_id']);
        $this->assertCount(0, $apiClient->createUserCalls);

        // Sincronização em lote deve detectar o ID 409 recém-criado para Kaia via reconsulta pontual
        $resultado = $service->sincronizarColaboradoresEmDispositivos([$dispositivo]);
        $this->assertCount(0, $apiClient->createUserCalls);
        $this->assertSame(0, $resultado['resumo']['criados']);

        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '409',
            'map_col_id' => $kaia->col_id,
            'map_ativo' => 1,
        ]);
        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_user_id' => '410',
            'map_col_id' => $katia->col_id,
            'map_ativo' => 1,
        ]);
    }

    public function testBloqueioConcorrentePorDispositivoImpedeExecucaoSimultaneaEControllerAguardaLock(): void
    {
        config(['rh.sync_lock_wait_seconds' => 0]);
        $this->be(new \Illuminate\Auth\GenericUser(['id' => 10, 'usr_id' => 10]));

        $dispositivo = $this->criarDispositivo('iDFace Lock');
        $colaborador = $this->criarColaboradorAtivo(true, 'Willian Mano Araújo');

        $apiClient = new FakeControlIdApiClient([
            $dispositivo->dis_id => [],
        ]);
        $this->app->instance(ControlIdApiClient::class, $apiClient);

        $controller = app(\Modulos\RH\Http\Controllers\DispositivoUsuariosController::class);

        $reqIniciar = \Illuminate\Http\Request::create('/rh/dispositivo-usuarios/sincronizar-todos', 'POST', [
            'dis_id' => $dispositivo->dis_id,
            'acao_lote' => 'iniciar',
            'dispositivos_destino' => [$dispositivo->dis_id],
        ]);
        $reqIniciar->headers->set('Accept', 'application/json');
        $dadosIniciar = $controller->postSincronizarTodos($reqIniciar)->getData(true);
        $token = $dadosIniciar['token'];

        // Simula outro worker segurando o lock exclusivo deste dispositivo
        $lockDispositivo = \Illuminate\Support\Facades\Cache::lock('sync_dispositivo_hw:' . $dispositivo->dis_id, 60);
        $this->assertTrue($lockDispositivo->get());

        try {
            $reqEnquantoBloqueado = \Illuminate\Http\Request::create('/rh/dispositivo-usuarios/sincronizar-todos', 'POST', [
                'dis_id' => $dispositivo->dis_id,
                'acao_lote' => 'processar',
                'token' => $token,
            ]);
            $reqEnquantoBloqueado->headers->set('Accept', 'application/json');

            $respBloqueado = $controller->postSincronizarTodos($reqEnquantoBloqueado);
            $this->assertSame(200, $respBloqueado->getStatusCode());
            $dadosBloqueado = $respBloqueado->getData(true);

            $this->assertFalse($dadosBloqueado['concluido']);
            $this->assertTrue($dadosBloqueado['aguardando_lock']);
            $this->assertSame(0, $dadosBloqueado['offset_atual']);
            $this->assertEmpty($dadosBloqueado['erros']);
            $this->assertCount(0, $apiClient->createUserCalls);
        } finally {
            $lockDispositivo->release();
        }

        // Liberado o lock, a próxima fatia processa normalmente e cria apenas 1 registro
        $reqAposLiberar = \Illuminate\Http\Request::create('/rh/dispositivo-usuarios/sincronizar-todos', 'POST', [
            'dis_id' => $dispositivo->dis_id,
            'acao_lote' => 'processar',
            'token' => $token,
        ]);
        $reqAposLiberar->headers->set('Accept', 'application/json');

        $respConcluido = $controller->postSincronizarTodos($reqAposLiberar);
        $this->assertSame(200, $respConcluido->getStatusCode());
        $dadosConcluido = $respConcluido->getData(true);

        $this->assertTrue($dadosConcluido['concluido']);
        $this->assertSame(1, $dadosConcluido['resumo']['criados']);
        $this->assertCount(1, $apiClient->createUserCalls);
        $this->assertDatabaseHas('reh_mapeamento_dispositivo', [
            'map_dis_id' => $dispositivo->dis_id,
            'map_col_id' => $colaborador->col_id,
            'map_ativo' => 1,
        ]);
    }

    public function testSincronizarEntreDispositivosNaoPropagaClonesDuplicadosDaOrigem(): void
    {
        $origem = $this->criarDispositivo('iDFace Origem Com Clones');
        $destino = $this->criarDispositivo('iDFace Destino Limpo', 'saida');
        $kaia = $this->criarColaboradorAtivo(true, 'KAIA JANAINA DE SOUSA BARROS');

        $apiClient = new FakeControlIdApiClient([
            $origem->dis_id => [
                ['id' => 409, 'registration' => (string) $kaia->col_id, 'name' => 'KAIA JANAINA DE SOUSA BARROS', 'has_image' => true],
                ['id' => 464, 'registration' => (string) $kaia->col_id, 'name' => 'KAIA JANAINA DE SOUSA BARROS', 'has_image' => true],
                ['id' => 498, 'registration' => (string) $kaia->col_id, 'name' => 'KAIA JANAINA DE SOUSA BARROS', 'has_image' => true],
            ],
            $destino->dis_id => [],
        ]);

        $service = $this->criarService($apiClient);
        $resultado = $service->sincronizarUsuariosEntreDispositivos($origem, $destino);

        $this->assertSame(1, $resultado['criados']);
        $this->assertSame(0, $resultado['atualizados']);
        $this->assertSame(2, $resultado['inalterados']);
        $this->assertCount(1, $apiClient->createUserCalls);
        $this->assertCount(1, $apiClient->usuariosDoDispositivo($destino->dis_id));
    }

    private function criarService(FakeControlIdApiClient $apiClient): SincronizacaoUsuariosDispositivoService
    {
        return new SincronizacaoUsuariosDispositivoService(
            $apiClient,
            new ColaboradorRepository(new Colaborador()),
            new MapeamentoDispositivoRepository(new MapeamentoDispositivo()),
            new AnexoRepository(new Anexo())
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

    private function criarColaboradorAtivo(bool $comFoto = true, ?string $nome = null): Colaborador
    {
        $pessoaId = DB::table('gra_pessoas')->insertGetId([
            'pes_nome' => $nome ?? ('Pessoa ' . uniqid()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $colaboradorId = DB::table('reh_colaboradores')->insertGetId([
            'col_pes_id' => $pessoaId,
            'col_foto_anx_id' => $comFoto ? $this->criarFotoFacialAnexo() : null,
            'col_status' => 'ativo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Colaborador::query()->findOrFail($colaboradorId);
    }

    private function criarFotoFacialAnexo(string $conteudo = null): int
    {
        $conteudo = $conteudo ?? ('face-' . uniqid('', true));
        $caminhoTemporario = tempnam(sys_get_temp_dir(), 'face-');
        file_put_contents($caminhoTemporario, $conteudo);

        $arquivo = new UploadedFile($caminhoTemporario, uniqid('face-', true) . '.jpg', 'image/jpeg', null, true);
        $anexo = app(AnexoRepository::class)->salvarAnexo($arquivo);

        if (is_array($anexo) || !$anexo) {
            throw new RuntimeException('Nao foi possivel preparar a foto facial para o teste.');
        }

        return (int) $anexo->anx_id;
    }

    private function conteudoFotoDoColaborador(Colaborador $colaborador): string
    {
        return app(AnexoRepository::class)->lerConteudoAnexo($colaborador->col_foto_anx_id);
    }

    private function criarSchemaMinimo(): void
    {
        Schema::dropAllTables();

        Schema::create('gra_pessoas', function (Blueprint $table) {
            $table->increments('pes_id');
            $table->string('pes_nome');
            $table->timestamps();
        });

        Schema::create('gra_anexos', function (Blueprint $table) {
            $table->increments('anx_id');
            $table->string('anx_nome');
            $table->string('anx_mime');
            $table->string('anx_extensao');
            $table->string('anx_localizacao');
            $table->timestamps();
        });

        Schema::create('reh_colaboradores', function (Blueprint $table) {
            $table->increments('col_id');
            $table->unsignedInteger('col_pes_id');
            $table->unsignedInteger('col_foto_anx_id')->nullable();
            $table->string('col_status', 20);
            $table->timestamps();

            $table->foreign('col_pes_id')->references('pes_id')->on('gra_pessoas');
            $table->foreign('col_foto_anx_id')->references('anx_id')->on('gra_anexos');
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

        Schema::create('seg_auditoria', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('log_usr_id')->nullable();
            $table->string('log_action', 20);
            $table->string('log_table', 100);
            $table->unsignedInteger('log_table_id')->nullable();
            $table->text('log_object')->nullable();
            $table->timestamps();
        });
    }
}

class FakeControlIdApiClient extends ControlIdApiClient
{
    public array $createUserCalls = [];
    public array $modifyUserCalls = [];
    public array $destroyUserCalls = [];
    public array $setUserImageCalls = [];
    public array $loadObjectsCalls = [];

    private array $usuariosPorDispositivo;
    private array $imagensPorDispositivoUsuario;
    private array $gruposPorDispositivo = [];
    private array $gruposUsuarioPorDispositivo = [];
    private array $imagensRejeitadas = [];
    private array $dispositivosInacessiveis = [];
    private array $usuariosConcorrentesPendentes = [];
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
        $this->loadObjectsCalls[] = [
            'device_id' => $dispositivo->dis_id,
            'object' => $object,
        ];

        if (isset($this->dispositivosInacessiveis[$dispositivo->dis_id])) {
            throw new \InvalidArgumentException(
                'Dispositivo inacessível',
                0,
                new \GuzzleHttp\Exception\ConnectException(
                    'Connection refused',
                    new \GuzzleHttp\Psr7\Request('POST', 'http://127.0.0.1/load_objects.fcgi')
                )
            );
        }

        if ($object === 'users') {
            $registrationFiltro = $filters['where']['users']['registration'] ?? null;

            if ($registrationFiltro !== null && isset($this->usuariosConcorrentesPendentes[$dispositivo->dis_id][$registrationFiltro])) {
                $injetado = $this->usuariosConcorrentesPendentes[$dispositivo->dis_id][$registrationFiltro];
                unset($this->usuariosConcorrentesPendentes[$dispositivo->dis_id][$registrationFiltro]);
                $idInjetado = (int) $injetado['id'];
                $this->usuariosPorDispositivo[$dispositivo->dis_id][$idInjetado] = array_merge([
                    'id' => $idInjetado,
                    'registration' => '',
                    'name' => '',
                    'has_image' => false,
                ], $injetado);
            }

            $listaUsuarios = array_values($this->usuariosPorDispositivo[$dispositivo->dis_id] ?? []);

            if ($registrationFiltro !== null) {
                $listaUsuarios = array_values(array_filter($listaUsuarios, function (array $u) use ($registrationFiltro) {
                    return (string) ($u['registration'] ?? '') === (string) $registrationFiltro;
                }));
            }

            return [
                'users' => $listaUsuarios,
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

    public function destroyUser(DispositivoAcesso $dispositivo, int $userId): array
    {
        $this->destroyUserCalls[] = [
            'device_id' => $dispositivo->dis_id,
            'user_id' => $userId,
        ];

        unset(
            $this->usuariosPorDispositivo[$dispositivo->dis_id][$userId],
            $this->imagensPorDispositivoUsuario[$dispositivo->dis_id][$userId]
        );

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

        if (isset($this->imagensRejeitadas[$imageBinary])) {
            return [
                'success' => false,
                'errors' => [[
                    'code' => 2,
                    'message' => $this->imagensRejeitadas[$imageBinary],
                ]],
            ];
        }

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

    public function rejeitarImagem(string $imageBinary, string $mensagem): void
    {
        $this->imagensRejeitadas[$imageBinary] = $mensagem;
    }

    public function marcarDispositivoInacessivel(int $dispositivoId): void
    {
        $this->dispositivosInacessiveis[$dispositivoId] = true;
    }

    public function injetarUsuarioConcorrenteAoConsultarRegistration(int $dispositivoId, string $registration, array $usuario): void
    {
        $this->usuariosConcorrentesPendentes[$dispositivoId][$registration] = $usuario;
    }
}
