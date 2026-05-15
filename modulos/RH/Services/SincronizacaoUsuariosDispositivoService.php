<?php

namespace Modulos\RH\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Models\MapeamentoDispositivo;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\MapeamentoDispositivoRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SincronizacaoUsuariosDispositivoService
{
    public function __construct(
        private ControlIdApiClient $apiClient,
        private ColaboradorRepository $colaboradorRepository,
        private MapeamentoDispositivoRepository $mapeamentoRepository
    ) {
    }

    public function consultar(DispositivoAcesso $dispositivo): array
    {
        $usuarios = $this->extrairUsuarios(
            $this->apiClient->loadObjects($dispositivo, 'users')
        );

        $mapeamentos = $this->mapeamentoRepository->listarPorDispositivoEUsuarios(
            $dispositivo->dis_id,
            array_values(array_filter(array_map(function (array $usuario) {
                return isset($usuario['id']) ? (string) $usuario['id'] : null;
            }, $usuarios)))
        );

        $usuariosCombinados = $this->combinarUsuariosComMapeamentos($usuarios, $mapeamentos->toArray());

        return [
            'usuarios' => $usuariosCombinados,
            'resumo' => $this->resumirUsuarios($usuariosCombinados),
        ];
    }

    public function sincronizar(DispositivoAcesso $dispositivo): array
    {
        $usuarios = $this->extrairUsuarios(
            $this->apiClient->loadObjects($dispositivo, 'users')
        );

        DB::transaction(function () use ($dispositivo, $usuarios) {
            $userIds = [];

            foreach ($usuarios as $usuario) {
                $userId = isset($usuario['id']) ? (string) $usuario['id'] : null;

                if ($userId === null || $userId === '') {
                    continue;
                }

                $userIds[] = $userId;

                $mapeamentoExistente = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($dispositivo->dis_id, $userId);
                $colaborador = $this->resolverColaboradorParaSincronizacao($mapeamentoExistente, $usuario);

                MapeamentoDispositivo::updateOrCreate(
                    [
                        'map_dis_id' => $dispositivo->dis_id,
                        'map_user_id' => $userId,
                    ],
                    [
                        'map_col_id' => $colaborador?->col_id,
                        'map_registration' => (string) ($usuario['registration'] ?? ''),
                        'map_nome_dispositivo' => (string) ($usuario['name'] ?? ''),
                        'map_ativo' => true,
                    ]
                );

                Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));
            }

            $query = MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id);

            if (!empty($userIds)) {
                $query->whereNotIn('map_user_id', $userIds);
            }

            $query->update(['map_ativo' => false]);
        });

        return $this->consultar($dispositivo);
    }

    public function criarUsuario(
        DispositivoAcesso $dispositivo,
        int $colaboradorId,
        array $dados = [],
        ?UploadedFile $foto = null
    ): array {
        $colaborador = $this->colaboradorRepository->buscarAtivoComPessoa($colaboradorId);

        if (!$colaborador || !$colaborador->pessoa) {
            throw new InvalidArgumentException('Colaborador ativo nao encontrado para cadastro no dispositivo.');
        }

        $mapeamentoExistente = $this->mapeamentoRepository->buscarPorDispositivoEColaborador($dispositivo->dis_id, $colaboradorId);

        if ($mapeamentoExistente) {
            $usuarioExistente = $this->buscarUsuario($dispositivo, (string) $mapeamentoExistente->map_user_id);

            if ($usuarioExistente) {
                throw new InvalidArgumentException('Este colaborador ja esta vinculado a um usuario neste dispositivo.');
            }

            $mapeamentoExistente->fill([
                'map_ativo' => false,
                'map_col_id' => null,
            ])->save();
        }

        $nome = trim((string) ($dados['nome'] ?? ''));
        $registration = trim((string) ($dados['registration'] ?? ''));

        $nome = $nome !== '' ? $nome : (string) $colaborador->pessoa->pes_nome;
        $registration = $registration !== '' ? $registration : (string) $colaborador->col_id;

        $resposta = $this->apiClient->createUser($dispositivo, [
            'registration' => $registration,
            'name' => $nome,
            'password' => '',
        ]);

        $userId = $this->resolverUserIdNaResposta($resposta);

        if ($userId !== null) {
            $this->garantirGrupoPadraoDoUsuario($dispositivo, $userId);
        }

        $sincronizacao = $this->sincronizar($dispositivo);
        $usuario = $this->buscarUsuarioSincronizado($sincronizacao['usuarios'], $userId, $registration);

        if (!$usuario) {
            throw new InvalidArgumentException('Usuario criado no dispositivo, mas nao foi possivel localiza-lo para concluir a sincronizacao.');
        }

        $this->vincularUsuario($dispositivo, (string) $usuario['user_id'], $colaboradorId);

        if ($foto) {
            $respostaFoto = $this->apiClient->setUserImage(
                $dispositivo,
                (int) $usuario['user_id'],
                $this->converterImagemParaBinario($foto)
            );

            $this->validarRespostaCadastroFacial($respostaFoto);
        }

        return $this->buscarUsuario($dispositivo, (string) $usuario['user_id']) ?? $usuario;
    }

    public function garantirUsuarioEmDispositivos(
        iterable $dispositivos,
        int $colaboradorId,
        array $dados = [],
        ?UploadedFile $foto = null
    ): array {
        $resultado = [
            'total' => 0,
            'criados' => 0,
            'atualizados' => 0,
            'dispositivos' => [],
            'erros' => [],
        ];

        foreach ($dispositivos as $dispositivo) {
            $resultado['total']++;

            try {
                $acao = $this->garantirUsuarioNoDispositivo($dispositivo, $colaboradorId, $dados, $foto);
                $resultado['dispositivos'][] = [
                    'dispositivo' => $dispositivo->dis_nome,
                    'acao' => $acao,
                ];
                $resultado[$acao === 'criado' ? 'criados' : 'atualizados']++;
            } catch (\Throwable $exception) {
                $resultado['erros'][] = [
                    'dispositivo' => $dispositivo->dis_nome,
                    'mensagem' => $exception->getMessage(),
                ];
            }
        }

        return $resultado;
    }

    public function sincronizarColaboradoresEmDispositivos(iterable $dispositivos): array
    {
        $colaboradores = $this->colaboradorRepository->listarAtivosParaExportacao();
        $resultado = [
            'dispositivos' => [],
            'erros' => [],
            'resumo' => [
                'dispositivos' => 0,
                'criados' => 0,
                'atualizados' => 0,
                'inalterados' => 0,
            ],
        ];

        foreach ($dispositivos as $dispositivo) {
            try {
                $resultadoDispositivo = $this->sincronizarColaboradoresNoDispositivo($dispositivo, $colaboradores);
                $resultado['dispositivos'][] = $resultadoDispositivo;
                $resultado['resumo']['dispositivos']++;
                $resultado['resumo']['criados'] += $resultadoDispositivo['criados'];
                $resultado['resumo']['atualizados'] += $resultadoDispositivo['atualizados'];
                $resultado['resumo']['inalterados'] += $resultadoDispositivo['inalterados'];
            } catch (\Throwable $exception) {
                $resultado['erros'][] = [
                    'dispositivo' => $dispositivo->dis_nome,
                    'mensagem' => $exception->getMessage(),
                ];
            }
        }

        return $resultado;
    }

    public function atualizarUsuario(
        DispositivoAcesso $dispositivo,
        string $userId,
        array $dados = [],
        ?UploadedFile $foto = null
    ): array {
        $nome = trim((string) ($dados['nome'] ?? ''));
        $registration = trim((string) ($dados['registration'] ?? ''));

        $payload = [];

        if ($nome !== '') {
            $payload['name'] = $nome;
        }

        if ($registration !== '') {
            $payload['registration'] = $registration;
        }

        if (!empty($payload)) {
            $this->apiClient->modifyUser($dispositivo, (int) $userId, $payload);
        }

        $this->garantirGrupoPadraoDoUsuario($dispositivo, $userId);

        if ($foto) {
            $respostaFoto = $this->apiClient->setUserImage(
                $dispositivo,
                (int) $userId,
                $this->converterImagemParaBinario($foto)
            );

            $this->validarRespostaCadastroFacial($respostaFoto);
        }

        if (!empty($dados['col_id'])) {
            $this->vincularUsuario($dispositivo, $userId, (int) $dados['col_id']);
        }

        $sincronizacao = $this->sincronizar($dispositivo);

        return $this->buscarUsuarioSincronizado($sincronizacao['usuarios'], $userId, $registration)
            ?? throw new InvalidArgumentException('Nao foi possivel localizar o usuario atualizado no dispositivo.');
    }

    public function removerUsuario(DispositivoAcesso $dispositivo, string $userId): void
    {
        $this->apiClient->destroyUser($dispositivo, (int) $userId);

        $mapeamento = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($dispositivo->dis_id, $userId);

        if ($mapeamento) {
            $mapeamento->fill([
                'map_ativo' => false,
                'map_col_id' => null,
            ])->save();

            Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));
        }
    }

    public function removerFotoUsuario(DispositivoAcesso $dispositivo, string $userId): void
    {
        $this->apiClient->destroyUserImage($dispositivo, (int) $userId);
    }

    public function vincularUsuario(DispositivoAcesso $dispositivo, string $userId, int $colaboradorId): MapeamentoDispositivo
    {
        $colaborador = $this->colaboradorRepository->buscarAtivoComPessoa($colaboradorId);

        if (!$colaborador) {
            throw new InvalidArgumentException('Colaborador ativo nao encontrado para vinculacao.');
        }

        if ($this->mapeamentoRepository->buscarPorDispositivoEColaborador($dispositivo->dis_id, $colaboradorId, $userId)) {
            throw new InvalidArgumentException('Este colaborador ja esta vinculado a outro usuario neste dispositivo.');
        }

        $mapeamento = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($dispositivo->dis_id, $userId);

        if (!$mapeamento) {
            $usuario = $this->buscarUsuario($dispositivo, $userId);

            if (!$usuario) {
                throw new InvalidArgumentException('Usuario do dispositivo nao encontrado para vinculacao.');
            }

            $mapeamento = new MapeamentoDispositivo([
                'map_dis_id' => $dispositivo->dis_id,
                'map_user_id' => $userId,
                'map_registration' => (string) ($usuario['registration'] ?? ''),
                'map_nome_dispositivo' => (string) ($usuario['nome'] ?? ''),
                'map_ativo' => true,
            ]);
        }

        $mapeamento->fill([
            'map_col_id' => $colaboradorId,
            'map_ativo' => true,
        ])->save();

        Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));

        return $mapeamento;
    }

    public function desvincularUsuario(DispositivoAcesso $dispositivo, string $userId): void
    {
        $mapeamento = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($dispositivo->dis_id, $userId);

        if (!$mapeamento) {
            throw new InvalidArgumentException('Mapeamento nao encontrado para desvinculacao.');
        }

        $mapeamento->fill([
            'map_col_id' => null,
            'map_ativo' => true,
        ])->save();

        Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));
    }

    public function buscarUsuario(DispositivoAcesso $dispositivo, string $userId): ?array
    {
        $consulta = $this->consultar($dispositivo);

        foreach ($consulta['usuarios'] as $usuario) {
            if ((string) $usuario['user_id'] === $userId) {
                return $usuario;
            }
        }

        return null;
    }

    public function extrairUsuarios(array $response): array
    {
        foreach (['users', 'groups', 'user_groups', 'values', 'objects'] as $key) {
            if (isset($response[$key]) && is_array($response[$key])) {
                return array_values(array_filter($response[$key], 'is_array'));
            }
        }

        if (count($response) === 1) {
            $primeiroValor = reset($response);

            if (is_array($primeiroValor)) {
                return array_values(array_filter($primeiroValor, 'is_array'));
            }
        }

        return array_values(array_filter($response, 'is_array'));
    }

    public function combinarUsuariosComMapeamentos(array $usuarios, array $mapeamentos): array
    {
        $mapeamentosPorUsuario = [];

        foreach ($mapeamentos as $mapeamento) {
            $userId = (string) ($mapeamento['map_user_id'] ?? '');

            if ($userId !== '') {
                $mapeamentosPorUsuario[$userId] = $mapeamento;
            }
        }

        return array_values(array_map(function (array $usuario) use ($mapeamentosPorUsuario) {
            $userId = isset($usuario['id']) ? (string) $usuario['id'] : '';
            $mapeamento = $mapeamentosPorUsuario[$userId] ?? [];

            $fotoOk = !empty($usuario['image_timestamp'])
                || !empty($usuario['has_image'])
                || !empty($usuario['image']);

            return [
                'user_id' => $userId,
                'registration' => (string) ($usuario['registration'] ?? ''),
                'nome' => (string) ($usuario['name'] ?? ''),
                'foto_ok' => $fotoOk,
                'col_id' => $mapeamento['map_col_id'] ?? null,
                'colaborador_nome' => $mapeamento['colaborador_nome'] ?? null,
                'map_ativo' => (bool) ($mapeamento['map_ativo'] ?? true),
                'status_vinculo' => !empty($mapeamento['map_col_id']) ? 'vinculado' : 'pendente',
            ];
        }, $usuarios));
    }

    public function resumirUsuarios(array $usuarios): array
    {
        $resumo = [
            'total' => count($usuarios),
            'vinculados' => 0,
            'pendentes' => 0,
            'com_foto' => 0,
            'sem_foto' => 0,
        ];

        foreach ($usuarios as $usuario) {
            if (!empty($usuario['col_id'])) {
                $resumo['vinculados']++;
            } else {
                $resumo['pendentes']++;
            }

            if (!empty($usuario['foto_ok'])) {
                $resumo['com_foto']++;
            } else {
                $resumo['sem_foto']++;
            }
        }

        return $resumo;
    }

    private function resolverColaboradorParaSincronizacao(?MapeamentoDispositivo $mapeamentoExistente, array $usuario)
    {
        if ($mapeamentoExistente && $mapeamentoExistente->map_col_id) {
            $colaborador = $this->colaboradorRepository->buscarAtivoComPessoa((int) $mapeamentoExistente->map_col_id);

            if ($colaborador) {
                return $colaborador;
            }
        }

        return $this->colaboradorRepository->buscarAtivoPorRegistration((string) ($usuario['registration'] ?? ''));
    }

    private function buscarUsuarioSincronizado(array $usuarios, ?string $userId = null, ?string $registration = null): ?array
    {
        foreach ($usuarios as $usuario) {
            if ($userId !== null && (string) $usuario['user_id'] === (string) $userId) {
                return $usuario;
            }

            if ($registration !== null && (string) $usuario['registration'] === (string) $registration) {
                return $usuario;
            }
        }

        return null;
    }

    private function resolverUserIdNaResposta(array $resposta): ?string
    {
        foreach (['id', 'user_id'] as $key) {
            if (isset($resposta[$key])) {
                return (string) $resposta[$key];
            }
        }

        if (!empty($resposta['ids']) && is_array($resposta['ids'])) {
            return (string) reset($resposta['ids']);
        }

        if (!empty($resposta['values']) && is_array($resposta['values'])) {
            $primeiro = reset($resposta['values']);

            if (is_array($primeiro) && isset($primeiro['id'])) {
                return (string) $primeiro['id'];
            }
        }

        return null;
    }

    private function converterImagemParaBinario(UploadedFile $foto): string
    {
        $conteudo = file_get_contents($foto->getRealPath());

        if ($conteudo === false) {
            throw new InvalidArgumentException('Nao foi possivel ler a imagem enviada para o dispositivo.');
        }

        return $conteudo;
    }

    private function validarRespostaCadastroFacial(array $resposta): void
    {
        if (($resposta['success'] ?? null) !== false) {
            return;
        }

        $erros = $resposta['errors'] ?? [];
        $primeiroErro = is_array($erros) ? reset($erros) : null;

        if (is_array($primeiroErro) && !empty($primeiroErro['message'])) {
            throw new InvalidArgumentException('O dispositivo rejeitou a foto facial: ' . $primeiroErro['message']);
        }

        throw new InvalidArgumentException('O dispositivo rejeitou a foto facial enviada.');
    }

    private function garantirUsuarioNoDispositivo(
        DispositivoAcesso $dispositivo,
        int $colaboradorId,
        array $dados = [],
        ?UploadedFile $foto = null
    ): string {
        $consulta = $this->consultar($dispositivo);
        $usuarioExistente = $this->localizarUsuarioDoColaborador($consulta['usuarios'], $colaboradorId);

        if ($usuarioExistente) {
            $this->atualizarUsuario($dispositivo, (string) $usuarioExistente['user_id'], array_merge($dados, [
                'col_id' => $colaboradorId,
            ]), $foto);

            return 'atualizado';
        }

        $this->criarUsuario($dispositivo, $colaboradorId, $dados, $foto);

        return 'criado';
    }

    private function sincronizarColaboradoresNoDispositivo(DispositivoAcesso $dispositivo, Collection $colaboradores): array
    {
        $consulta = $this->consultar($dispositivo);
        $usuarios = $consulta['usuarios'];
        $resultado = [
            'dispositivo' => $dispositivo->dis_nome,
            'criados' => 0,
            'atualizados' => 0,
            'inalterados' => 0,
        ];

        foreach ($colaboradores as $colaborador) {
            $dadosDesejados = [
                'nome' => (string) $colaborador->pes_nome,
                'registration' => (string) $colaborador->col_id,
                'col_id' => (int) $colaborador->col_id,
            ];

            $usuarioExistente = $this->localizarUsuarioDoColaborador($usuarios, (int) $colaborador->col_id);

            if (!$usuarioExistente) {
                $resposta = $this->apiClient->createUser($dispositivo, [
                    'registration' => $dadosDesejados['registration'],
                    'name' => $dadosDesejados['nome'],
                    'password' => '',
                ]);

                $userId = $this->resolverUserIdNaResposta($resposta);

                if ($userId !== null) {
                    $this->garantirGrupoPadraoDoUsuario($dispositivo, $userId);
                }

                $resultado['criados']++;
                continue;
            }

            $precisaAtualizar = (string) ($usuarioExistente['registration'] ?? '') !== $dadosDesejados['registration']
                || (string) ($usuarioExistente['nome'] ?? '') !== $dadosDesejados['nome']
                || (int) ($usuarioExistente['col_id'] ?? 0) !== $dadosDesejados['col_id'];

            if (!$precisaAtualizar) {
                $resultado['inalterados']++;
                continue;
            }

            $this->atualizarUsuarioExistenteNoDispositivo($dispositivo, (string) $usuarioExistente['user_id'], $dadosDesejados);
            $resultado['atualizados']++;
        }

        $this->sincronizar($dispositivo);

        return $resultado;
    }

    private function localizarUsuarioDoColaborador(array $usuarios, int $colaboradorId): ?array
    {
        $registration = (string) $colaboradorId;

        foreach ($usuarios as $usuario) {
            if ((int) ($usuario['col_id'] ?? 0) === $colaboradorId) {
                return $usuario;
            }

            if ((string) ($usuario['registration'] ?? '') === $registration) {
                return $usuario;
            }
        }

        return null;
    }

    private function atualizarUsuarioExistenteNoDispositivo(
        DispositivoAcesso $dispositivo,
        string $userId,
        array $dados
    ): void {
        $payload = [];

        if (!empty($dados['nome'])) {
            $payload['name'] = (string) $dados['nome'];
        }

        if (!empty($dados['registration'])) {
            $payload['registration'] = (string) $dados['registration'];
        }

        if (!empty($payload)) {
            $this->apiClient->modifyUser($dispositivo, (int) $userId, $payload);
        }

        $this->garantirGrupoPadraoDoUsuario($dispositivo, $userId);

        if (!empty($dados['col_id'])) {
            $this->vincularUsuario($dispositivo, $userId, (int) $dados['col_id']);
        }
    }

    private function garantirGrupoPadraoDoUsuario(DispositivoAcesso $dispositivo, string $userId): void
    {
        $groupId = $this->resolverGrupoPadraoId($dispositivo);

        if ($groupId === null) {
            Log::channel('ponto')->warning('Nenhum grupo padrao foi encontrado no dispositivo para vincular o usuario criado.', [
                'dispositivo_id' => $dispositivo->dis_id,
                'user_id' => $userId,
            ]);

            return;
        }

        $vinculos = $this->extrairUsuarios($this->apiClient->loadObjects($dispositivo, 'user_groups', [
            'where' => [
                'user_groups' => [
                    'user_id' => (int) $userId,
                ],
            ],
        ]));

        foreach ($vinculos as $vinculo) {
            if ((int) ($vinculo['group_id'] ?? 0) === $groupId) {
                return;
            }
        }

        $this->apiClient->createUserGroup($dispositivo, (int) $userId, $groupId);
    }

    private function resolverGrupoPadraoId(DispositivoAcesso $dispositivo): ?int
    {
        $grupos = $this->extrairUsuarios($this->apiClient->loadObjects($dispositivo, 'groups', [
            'fields' => ['id', 'name'],
            'order' => ['id', 'ascending'],
        ]));

        if (empty($grupos)) {
            return null;
        }

        $nomesPadrao = ['padrao', 'padrao', 'standard'];

        foreach ($grupos as $grupo) {
            $nome = mb_strtolower(trim((string) ($grupo['name'] ?? '')));

            if (in_array($nome, $nomesPadrao, true)) {
                return (int) $grupo['id'];
            }
        }

        foreach ($grupos as $grupo) {
            if ((int) ($grupo['id'] ?? 0) === 1) {
                return 1;
            }
        }

        return isset($grupos[0]['id']) ? (int) $grupos[0]['id'] : null;
    }
}
