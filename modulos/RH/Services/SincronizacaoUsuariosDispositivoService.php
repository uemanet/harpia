<?php

namespace Modulos\RH\Services;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Models\MapeamentoDispositivo;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\MapeamentoDispositivoRepository;

class SincronizacaoUsuariosDispositivoService
{
    private array $cacheFotosBinarias = [];

    private array $locksDispositivoAtivos = [];

    public function __construct(
        private ControlIdApiClient $apiClient,
        private ColaboradorRepository $colaboradorRepository,
        private MapeamentoDispositivoRepository $mapeamentoRepository,
        private AnexoRepository $anexoRepository
    ) {
    }

    public function consultar(DispositivoAcesso $dispositivo): array
    {
        $usuarios = $this->extrairUsuarios(
            $this->apiClient->loadObjects($dispositivo, 'users')
        );

        return $this->montarConsultaUsuarios((int) $dispositivo->dis_id, $usuarios);
    }

    public function sincronizar(DispositivoAcesso $dispositivo): array
    {
        return $this->executarComLockDispositivo($dispositivo, function () use ($dispositivo) {
            $usuarios = $this->extrairUsuarios(
                $this->apiClient->loadObjects($dispositivo, 'users')
            );

            $this->reconciliarMapeamentosComUsuariosLidos($dispositivo, $usuarios);

            return $this->montarConsultaUsuarios((int) $dispositivo->dis_id, $usuarios);
        });
    }

    public function criarUsuario(DispositivoAcesso $dispositivo, int $colaboradorId): array
    {
        return $this->executarComLockDispositivo($dispositivo, function () use ($dispositivo, $colaboradorId) {
            $colaborador = $this->buscarColaboradorParaDispositivo($colaboradorId);
            $dadosDesejados = $this->montarDadosDispositivoDoColaborador($colaborador);
            $fotoBinaria = $this->obterFotoBinariaDoColaborador($colaborador);

            $mapeamentoExistente = $this->mapeamentoRepository->buscarPorDispositivoEColaborador($dispositivo->dis_id, $colaboradorId);

            if ($mapeamentoExistente) {
                $usuarioExistente = $this->buscarUsuario($dispositivo, (string) $mapeamentoExistente->map_user_id);

                if ($usuarioExistente) {
                    throw new InvalidArgumentException('Este colaborador já está vinculado a um usuário neste dispositivo.');
                }

                $mapeamentoExistente->fill([
                    'map_ativo' => false,
                    'map_col_id' => null,
                ])->save();
            }

            $consultaAtual = $this->consultar($dispositivo);
            $usuarioJaNoHardware = $this->localizarUsuarioDoColaborador($consultaAtual['usuarios'], $colaboradorId);

            if ($usuarioJaNoHardware) {
                $userIdExistente = (string) $usuarioJaNoHardware['user_id'];
                $usuariosMemoria = $consultaAtual['usuarios'];
                $this->removerClonesDuplicadosDoColaborador($dispositivo, $colaboradorId, $userIdExistente, $usuariosMemoria, [$userIdExistente => true]);

                return $this->atualizarUsuario($dispositivo, $userIdExistente, $colaboradorId, $fotoBinaria);
            }

            $resposta = $this->apiClient->createUser($dispositivo, [
                'registration' => $dadosDesejados['registration'],
                'name' => $dadosDesejados['nome'],
                'password' => '',
            ]);

            $userId = $this->resolverUserIdNaResposta($resposta);

            if ($userId !== null) {
                $this->garantirGrupoPadraoDoUsuario($dispositivo, $userId);
            }

            $sincronizacao = $this->sincronizar($dispositivo);
            $usuario = $this->buscarUsuarioSincronizado($sincronizacao['usuarios'], $userId, $dadosDesejados['registration']);

            if (!$usuario) {
                throw new InvalidArgumentException('Usuário criado no dispositivo, mas não foi possível localizá-lo para concluir a sincronização.');
            }

            $this->vincularUsuario($dispositivo, (string) $usuario['user_id'], $colaboradorId);
            $respostaFoto = $this->apiClient->setUserImage(
                $dispositivo,
                (int) $usuario['user_id'],
                $fotoBinaria
            );

            $this->validarRespostaCadastroFacial($respostaFoto);

            return $this->buscarUsuario($dispositivo, (string) $usuario['user_id']) ?? $usuario;
        });
    }

    public function garantirUsuarioEmDispositivos(iterable $dispositivos, int $colaboradorId): array
    {
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
                $acao = $this->garantirUsuarioNoDispositivo($dispositivo, $colaboradorId);
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
        $this->cacheFotosBinarias = [];
        $colaboradores = $this->colaboradorRepository->listarAtivosParaExportacao();
        $todasFalhas = [];
        $resultado = [
            'dispositivos' => [],
            'erros' => [],
            'falhas_colaboradores' => [],
            'duplicados_removidos' => 0,
            'resumo' => [
                'dispositivos' => 0,
                'criados' => 0,
                'atualizados' => 0,
                'fotos_sincronizadas' => 0,
                'pendentes_foto' => 0,
                'inalterados' => 0,
                'falhas' => 0,
            ],
        ];

        try {
            foreach ($dispositivos as $dispositivo) {
                try {
                    $resultadoDispositivo = $this->sincronizarColaboradoresNoDispositivo($dispositivo, $colaboradores);
                    $resultado['dispositivos'][] = $resultadoDispositivo;
                    $resultado['duplicados_removidos'] += $resultadoDispositivo['duplicados_removidos'] ?? 0;
                    $resultado['resumo']['dispositivos']++;
                    $resultado['resumo']['criados'] += $resultadoDispositivo['criados'];
                    $resultado['resumo']['atualizados'] += $resultadoDispositivo['atualizados'];
                    $resultado['resumo']['fotos_sincronizadas'] += $resultadoDispositivo['fotos_sincronizadas'];
                    $resultado['resumo']['pendentes_foto'] += $resultadoDispositivo['pendentes_foto'];
                    $resultado['resumo']['inalterados'] += $resultadoDispositivo['inalterados'];
                    $resultado['resumo']['falhas'] += count($resultadoDispositivo['falhas']);

                    foreach ($resultadoDispositivo['falhas'] as $falha) {
                        $todasFalhas[] = $falha;
                    }
                } catch (\Throwable $exception) {
                    $resultado['erros'][] = [
                        'dispositivo' => $dispositivo->dis_nome,
                        'mensagem' => $this->extrairMensagemErroColaborador($exception),
                    ];
                }
            }
        } finally {
            $this->cacheFotosBinarias = [];
        }

        $resultado['falhas_colaboradores'] = $this->agruparFalhasColaboradores($todasFalhas);

        return $resultado;
    }

    public function atualizarUsuario(
        DispositivoAcesso $dispositivo,
        string $userId,
        int $colaboradorId,
        ?string $fotoBinariaManual = null
    ): array
    {
        $colaborador = $this->buscarColaboradorParaDispositivo($colaboradorId);
        $dadosDesejados = $this->montarDadosDispositivoDoColaborador($colaborador);
        $fotoBinaria = $fotoBinariaManual ?? $this->obterFotoBinariaDoColaborador($colaborador);

        $this->apiClient->modifyUser($dispositivo, (int) $userId, [
            'name' => $dadosDesejados['nome'],
            'registration' => $dadosDesejados['registration'],
        ]);

        $this->garantirGrupoPadraoDoUsuario($dispositivo, $userId);

        $respostaFoto = $this->apiClient->setUserImage(
            $dispositivo,
            (int) $userId,
            $fotoBinaria
        );

        $this->validarRespostaCadastroFacial($respostaFoto);

        $this->vincularUsuario($dispositivo, $userId, $colaboradorId);

        $sincronizacao = $this->sincronizar($dispositivo);

        return $this->buscarUsuarioSincronizado($sincronizacao['usuarios'], $userId, $dadosDesejados['registration'])
            ?? throw new InvalidArgumentException('Não foi possível localizar o usuário atualizado no dispositivo.');
    }

    public function atualizarUsuarioEmTodosDispositivos(
        DispositivoAcesso $dispositivoAtual,
        string $userId,
        int $colaboradorId,
        ?string $fotoBinariaManual = null
    ): array {
        $resultado = [
            'atualizados' => 0,
            'erros' => 0,
            'dispositivos' => [],
            'detalhes_erros' => [],
        ];

        $usuarioAtual = $this->buscarUsuario($dispositivoAtual, $userId);
        $registrationAnterior = (string) ($usuarioAtual['registration'] ?? '');
        $colaborador = $this->buscarColaboradorParaDispositivo($colaboradorId);
        $dadosDesejados = $this->montarDadosDispositivoDoColaborador($colaborador);
        $fotoBinaria = $fotoBinariaManual ?? $this->obterFotoBinariaDoColaborador($colaborador);

        try {
            $this->atualizarUsuario($dispositivoAtual, $userId, $colaboradorId, $fotoBinariaManual);
            $resultado['atualizados']++;
            $resultado['dispositivos'][] = $dispositivoAtual->dis_nome;
        } catch (\Throwable $e) {
            $resultado['erros']++;
            $resultado['detalhes_erros'][] = $dispositivoAtual->dis_nome . ': ' . $e->getMessage();
        }

        $registrationsRelacionados = array_values(array_unique(array_filter([
            $registrationAnterior,
            $dadosDesejados['registration'],
        ])));

        if (!empty($registrationsRelacionados)) {
            $outrosDispositivos = DispositivoAcesso::where('dis_status', 'ativo')
                ->where('dis_id', '<>', $dispositivoAtual->dis_id)
                ->get();

            foreach ($outrosDispositivos as $outro) {
                try {
                    $usuarios = $this->extrairUsuarios(
                        $this->apiClient->loadObjects($outro, 'users')
                    );

                    $usuarioRelacionado = $this->localizarUsuarioPorRegistrations($usuarios, $registrationsRelacionados);

                    if (!$usuarioRelacionado) {
                        $resultado['erros']++;
                        $resultado['detalhes_erros'][] = $outro->dis_nome . ': usuário relacionado não encontrado';
                        continue;
                    }

                    $usuarioRelacionadoId = (string) ($usuarioRelacionado['user_id'] ?? $usuarioRelacionado['id'] ?? '');

                    if ($usuarioRelacionadoId === '') {
                        $resultado['erros']++;
                        $resultado['detalhes_erros'][] = $outro->dis_nome . ': usuário relacionado sem identificador válido';
                        continue;
                    }

                    $this->apiClient->modifyUser($outro, (int) $usuarioRelacionadoId, [
                        'name' => $dadosDesejados['nome'],
                        'registration' => $dadosDesejados['registration'],
                    ]);

                    $respostaFoto = $this->apiClient->setUserImage(
                        $outro,
                        (int) $usuarioRelacionadoId,
                        $fotoBinaria
                    );

                    $this->validarRespostaCadastroFacial($respostaFoto);
                    $this->vincularUsuario($outro, $usuarioRelacionadoId, $colaboradorId);
                    $this->garantirGrupoPadraoDoUsuario($outro, $usuarioRelacionadoId);
                    $this->sincronizar($outro);

                    $resultado['atualizados']++;
                    $resultado['dispositivos'][] = $outro->dis_nome;
                } catch (\Throwable $e) {
                    $resultado['erros']++;
                    $resultado['detalhes_erros'][] = $outro->dis_nome . ': ' . $e->getMessage();
                }
            }
        }

        return $resultado;
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

    public function removerUsuarioDeTodosDispositivos(DispositivoAcesso $dispositivoAtual, string $userId): array
    {
        $usuario = $this->buscarUsuario($dispositivoAtual, $userId);
        $registration = (string) ($usuario['registration'] ?? '');

        $resultado = [
            'removidos' => 0,
            'erros' => 0,
            'dispositivos' => [],
        ];

        // Remove do dispositivo atual primeiro
        try {
            $this->removerUsuario($dispositivoAtual, $userId);
            $resultado['removidos']++;
            $resultado['dispositivos'][] = $dispositivoAtual->dis_nome;
        } catch (\Throwable $e) {
            $resultado['erros']++;
        }

        // Se tem registration, remove dos outros dispositivos
        if ($registration !== '') {
            $outrosDispositivos = DispositivoAcesso::where('dis_status', 'ativo')
                ->where('dis_id', '<>', $dispositivoAtual->dis_id)
                ->get();

            foreach ($outrosDispositivos as $outro) {
                try {
                    $usuarios = $this->extrairUsuarios(
                        $this->apiClient->loadObjects($outro, 'users')
                    );

                    foreach ($usuarios as $user) {
                        if ((string) ($user['registration'] ?? '') === $registration) {
                            $this->apiClient->destroyUser($outro, (int) $user['id']);

                            $map = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($outro->dis_id, (string) $user['id']);
                            if ($map) {
                                $map->fill(['map_ativo' => false, 'map_col_id' => null])->save();
                                Cache::forget(sprintf('matching:%d:%s', $outro->dis_id, (string) $user['id']));
                            }

                            $resultado['removidos']++;
                            $resultado['dispositivos'][] = $outro->dis_nome;
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    $resultado['erros']++;
                }
            }
        }

        return $resultado;
    }

    public function vincularUsuario(DispositivoAcesso $dispositivo, string $userId, int $colaboradorId): MapeamentoDispositivo
    {
        $colaborador = $this->colaboradorRepository->buscarAtivoComPessoa($colaboradorId);
        $dadosDesejados = $colaborador ? $this->montarDadosDispositivoDoColaborador($colaborador) : null;

        if (!$colaborador) {
            throw new InvalidArgumentException('Colaborador ativo não encontrado para vinculação.');
        }

        $conflito = $this->mapeamentoRepository->buscarPorDispositivoEColaborador($dispositivo->dis_id, $colaboradorId, $userId);

        if ($conflito) {
            $usuarioConflitante = $this->buscarUsuario($dispositivo, (string) $conflito->map_user_id);

            if ($usuarioConflitante) {
                throw new InvalidArgumentException('Este colaborador já está vinculado a outro usuário neste dispositivo.');
            }

            $conflito->fill([
                'map_ativo' => false,
                'map_col_id' => null,
            ])->save();
            Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, (string) $conflito->map_user_id));
        }

        $mapeamento = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($dispositivo->dis_id, $userId);

        if (!$mapeamento) {
            $usuario = $this->buscarUsuario($dispositivo, $userId);

            if (!$usuario) {
                throw new InvalidArgumentException('Usuário do dispositivo não encontrado para vinculação.');
            }

            $mapeamento = new MapeamentoDispositivo([
                'map_dis_id' => $dispositivo->dis_id,
                'map_user_id' => $userId,
                'map_registration' => (string) ($usuario['registration'] ?? ''),
                'map_nome_dispositivo' => (string) ($usuario['nome'] ?? ''),
                'map_ativo' => true,
            ]);
        }

        $this->apiClient->modifyUser($dispositivo, (int) $userId, [
            'name' => $dadosDesejados['nome'],
            'registration' => $dadosDesejados['registration'],
        ]);

        MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
            ->where('map_user_id', '<>', $userId)
            ->where('map_col_id', $colaboradorId)
            ->update(['map_col_id' => null]);

        $mapeamento->fill([
            'map_col_id' => $colaboradorId,
            'map_registration' => $dadosDesejados['registration'],
            'map_nome_dispositivo' => $dadosDesejados['nome'],
            'map_ativo' => true,
        ])->save();

        Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));

        return $mapeamento;
    }

    public function desvincularUsuario(DispositivoAcesso $dispositivo, string $userId): void
    {
        $mapeamento = $this->mapeamentoRepository->buscarPorDispositivoEUsuario($dispositivo->dis_id, $userId);

        if (!$mapeamento) {
            throw new InvalidArgumentException('Mapeamento não encontrado para desvinculação.');
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
                'image_timestamp' => isset($usuario['image_timestamp']) ? (int) $usuario['image_timestamp'] : 0,
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

    public function converterImagemParaBinario($foto): string
    {
        $conteudo = file_get_contents($foto->getRealPath());

        if ($conteudo === false) {
            throw new InvalidArgumentException('Não foi possível ler a imagem enviada para o dispositivo.');
        }

        return $conteudo;
    }

    private function traduzirMensagemDispositivo(string $mensagem): string
    {
        $chave = strtolower(rtrim(trim($mensagem), '.'));

        $traducoes = [
            'face too close to image borders' => 'Rosto muito próximo das bordas da imagem',
            'face not detected' => 'Nenhum rosto detectado na imagem',
            'no face detected' => 'Nenhum rosto detectado na imagem',
            'multiple faces detected' => 'Mais de um rosto detectado na imagem',
            'more than one face detected' => 'Mais de um rosto detectado na imagem',
            'face too small' => 'Rosto muito pequeno ou distante na imagem',
            'face is too small' => 'Rosto muito pequeno ou distante na imagem',
            'face too large' => 'Rosto muito grande ou próximo na imagem',
            'low quality face' => 'Qualidade da imagem insuficiente para reconhecimento',
            'poor image quality' => 'Qualidade da imagem insuficiente para reconhecimento',
            'low image quality' => 'Qualidade da imagem insuficiente para reconhecimento',
            'face not centered' => 'Rosto fora do centro da imagem',
            'invalid face pose' => 'Rosto inclinado ou virado (envie uma foto frontal)',
            'face pose too large' => 'Rosto inclinado ou virado (envie uma foto frontal)',
            'pose angle too large' => 'Rosto inclinado ou virado (envie uma foto frontal)',
            'face already exists' => 'Este rosto já está cadastrado para outro usuário no dispositivo',
            'user already exists' => 'Este usuário já está cadastrado no dispositivo',
            'invalid image' => 'Formato de imagem inválido para o dispositivo',
            'invalid image format' => 'Formato de imagem inválido para o dispositivo',
            'image too long. maximum size expected is 1920x1080' => 'Imagem acima da resolução máxima permitida (1920x1080)',
        ];

        return $traducoes[$chave] ?? $mensagem;
    }

    private function validarRespostaCadastroFacial(array $resposta): void
    {
        if (($resposta['success'] ?? null) !== false) {
            return;
        }

        $erros = $resposta['errors'] ?? [];
        $primeiroErro = is_array($erros) ? reset($erros) : null;

        if (is_array($primeiroErro) && !empty($primeiroErro['message'])) {
            throw new InvalidArgumentException('O dispositivo rejeitou a foto facial: ' . $this->traduzirMensagemDispositivo((string) $primeiroErro['message']));
        }

        throw new InvalidArgumentException('O dispositivo rejeitou a foto facial enviada.');
    }

    private function garantirUsuarioNoDispositivo(DispositivoAcesso $dispositivo, int $colaboradorId): string
    {
        return $this->executarComLockDispositivo($dispositivo, function () use ($dispositivo, $colaboradorId) {
            $colaborador = $this->buscarColaboradorParaDispositivo($colaboradorId);
            $colaboradoresAtivos = $this->colaboradorRepository->listarAtivosParaExportacao();
            $indiceColaboradores = $this->construirIndiceColaboradores($colaboradoresAtivos);
            $colaboradorIndexado = $indiceColaboradores['por_id'][$colaboradorId] ?? $colaborador;

            $consulta = $this->consultar($dispositivo);
            $usuarios = $consulta['usuarios'];
            $usuarioExistente = $this->localizarUsuarioDoColaboradorParaSincronizacao(
                $usuarios,
                $colaboradorIndexado,
                [],
                $indiceColaboradores
            );

            if ($usuarioExistente) {
                $userId = (string) $usuarioExistente['user_id'];
                $this->removerClonesDuplicadosDoColaborador(
                    $dispositivo,
                    $colaboradorId,
                    $userId,
                    $usuarios,
                    [$userId => true]
                );
                $this->atualizarUsuario($dispositivo, $userId, $colaboradorId);

                return 'atualizado';
            }

            $this->criarUsuario($dispositivo, $colaboradorId);

            return 'criado';
        });
    }

    public function sincronizarLoteColaboradoresNoDispositivo(
        DispositivoAcesso $dispositivo,
        int $offset = 0,
        int $maxEscritas = 5,
        int $maxInspecionados = 60,
        float $orcamentoSegundos = 8.0,
        ?Collection $colaboradores = null,
        int $esperaLockSegundos = 15
    ): array {
        return $this->executarComLockDispositivo($dispositivo, function () use (
            $dispositivo,
            $offset,
            $maxEscritas,
            $maxInspecionados,
            $orcamentoSegundos,
            $colaboradores
        ) {
            $inicio = microtime(true);
            $colaboradores = ($colaboradores ?? $this->colaboradorRepository->listarAtivosParaExportacao())->values();
            $totalColaboradores = $colaboradores->count();
            $offset = max(0, min($offset, $totalColaboradores));

            $indiceColaboradores = $this->construirIndiceColaboradores($colaboradores);

            $usuariosBrutos = $this->extrairUsuarios(
                $this->apiClient->loadObjects($dispositivo, 'users')
            );

            $userIdsNoDispositivo = array_values(array_filter(array_map(function (array $usuario) {
                return isset($usuario['id']) && (string) $usuario['id'] !== '' ? (string) $usuario['id'] : null;
            }, $usuariosBrutos)));

            $this->desativarMapeamentosAusentes((int) $dispositivo->dis_id, $userIdsNoDispositivo);

            $consulta = $this->montarConsultaUsuarios((int) $dispositivo->dis_id, $usuariosBrutos);
            $usuarios = $consulta['usuarios'];

            $contextoGrupos = $this->inicializarContextoGrupos();
            $userIdsReservados = [];
            $escritasHardware = 0;
            $inspecionados = 0;
            $proximoOffset = $offset;

            $resultado = [
                'dispositivo' => $dispositivo->dis_nome,
                'dis_id' => (int) $dispositivo->dis_id,
                'offset_inicial' => $offset,
                'proximo_offset' => $offset,
                'total_colaboradores' => $totalColaboradores,
                'concluido' => $offset >= $totalColaboradores,
                'criados' => 0,
                'atualizados' => 0,
                'fotos_sincronizadas' => 0,
                'pendentes_foto' => 0,
                'inalterados' => 0,
                'duplicados_removidos' => 0,
                'falhas' => [],
            ];

            for ($i = $offset; $i < $totalColaboradores; $i++) {
                if ($inspecionados > 0 && $maxEscritas > 0) {
                    if (
                        $escritasHardware >= $maxEscritas
                        || $inspecionados >= $maxInspecionados
                        || (microtime(true) - $inicio) >= $orcamentoSegundos
                    ) {
                        break;
                    }
                }

                $colaborador = $colaboradores[$i];
                $inspecionados++;
                $proximoOffset = $i + 1;
                $duplicadosRemovidosItem = 0;

                // Uma falha individual (ex.: foto rejeitada pelo dispositivo ou arquivo ausente)
                // não deve interromper a sincronização dos demais colaboradores.
                try {
                    $execucao = $this->sincronizarColaboradorNoDispositivo(
                        $dispositivo,
                        $colaborador,
                        $usuarios,
                        $userIdsReservados,
                        $indiceColaboradores,
                        $contextoGrupos,
                        $duplicadosRemovidosItem
                    );

                    $acao = $execucao['acao'];
                    $resultado[$acao]++;
                    $duplicadosRemovidosItem = (int) ($execucao['duplicados_removidos'] ?? $duplicadosRemovidosItem);
                    $resultado['duplicados_removidos'] += $duplicadosRemovidosItem;

                    if (!empty($execucao['foto_enviada'])) {
                        $resultado['fotos_sincronizadas']++;
                    }

                    if (!empty($execucao['pendente_foto']) && $acao !== 'pendentes_foto') {
                        $resultado['pendentes_foto']++;
                    }

                    if (!empty($execucao['escrita_hardware'])) {
                        $escritasHardware++;
                    }

                    if ($duplicadosRemovidosItem > 0) {
                        $escritasHardware += (int) ceil($duplicadosRemovidosItem / 3);
                    }
                } catch (\Throwable $exception) {
                    $resultado['duplicados_removidos'] += $duplicadosRemovidosItem;

                    if ($exception instanceof ConnectException || $exception->getPrevious() instanceof ConnectException) {
                        throw $exception;
                    }

                    $escritasHardware++;
                    $identificacao = $this->identificarColaboradorParaRelatorio($colaborador);
                    $mensagem = $this->extrairMensagemErroColaborador($exception);

                    $resultado['falhas'][] = [
                        'dispositivo' => $dispositivo->dis_nome,
                        'col_id' => $identificacao['col_id'],
                        'nome' => $identificacao['nome'],
                        'registration' => $identificacao['registration'],
                        'colaborador' => $identificacao['descricao'],
                        'mensagem' => $mensagem,
                    ];

                    Log::warning('Falha ao sincronizar colaborador no dispositivo.', [
                        'dis_id' => $dispositivo->dis_id,
                        'dispositivo' => $dispositivo->dis_nome,
                        'col_id' => $identificacao['col_id'],
                        'colaborador' => $identificacao['descricao'],
                        'erro' => $mensagem,
                    ]);
                }
            }

            $resultado['proximo_offset'] = $proximoOffset;
            $resultado['concluido'] = $proximoOffset >= $totalColaboradores;

            if ($resultado['concluido']) {
                $usuariosFinais = $escritasHardware > 0
                    ? $this->extrairUsuarios($this->apiClient->loadObjects($dispositivo, 'users'))
                    : $usuariosBrutos;
                $this->reconciliarMapeamentosComUsuariosLidos($dispositivo, $usuariosFinais, $indiceColaboradores);
            }

            return $resultado;
        }, $esperaLockSegundos);
    }

    private function sincronizarColaboradoresNoDispositivo(DispositivoAcesso $dispositivo, Collection $colaboradores): array
    {
        $lote = $this->sincronizarLoteColaboradoresNoDispositivo(
            $dispositivo,
            0,
            0,
            PHP_INT_MAX,
            INF,
            $colaboradores,
            15
        );

        return [
            'dispositivo' => $lote['dispositivo'],
            'criados' => $lote['criados'],
            'atualizados' => $lote['atualizados'],
            'fotos_sincronizadas' => $lote['fotos_sincronizadas'],
            'pendentes_foto' => $lote['pendentes_foto'],
            'inalterados' => $lote['inalterados'],
            'duplicados_removidos' => $lote['duplicados_removidos'],
            'falhas' => $lote['falhas'],
        ];
    }

    /**
     * Sincroniza um colaborador no dispositivo, persistindo o mapeamento imediatamente,
     * prevenindo criações duplicadas e consolidando clones redundantes.
     */
    private function sincronizarColaboradorNoDispositivo(
        DispositivoAcesso $dispositivo,
        $colaborador,
        array &$usuarios,
        array &$userIdsReservados,
        array $indiceColaboradores,
        array &$contextoGrupos,
        int &$duplicadosRemovidosOut = 0
    ): array {
        $dadosDesejados = $this->montarDadosDispositivoDoColaborador($colaborador);
        $temFotoNoHarpia = !empty($colaborador->col_foto_anx_id);

        $usuarioExistente = $this->localizarUsuarioDoColaboradorParaSincronizacao(
            $usuarios,
            $colaborador,
            $userIdsReservados,
            $indiceColaboradores
        );

        if (!$usuarioExistente) {
            if (!$temFotoNoHarpia) {
                return [
                    'acao' => 'pendentes_foto',
                    'foto_enviada' => false,
                    'pendente_foto' => true,
                    'escrita_hardware' => false,
                    'duplicados_removidos' => 0,
                ];
            }

            // Reconsulta pontual no hardware antes de criar para impedir duplicidade caso
            // outro processo externo ou requisição anterior tenha criado o registro há instantes.
            $usuarioExistente = $this->buscarUsuarioRecenteNoHardware(
                $dispositivo,
                $dadosDesejados['col_id'],
                $dadosDesejados['registration'],
                $usuarios,
                $userIdsReservados
            );
        }

        if (!$usuarioExistente) {
            $fotoBinaria = $this->obterFotoBinariaDoColaborador($colaborador);

            $resposta = $this->apiClient->createUser($dispositivo, [
                'registration' => $dadosDesejados['registration'],
                'name' => $dadosDesejados['nome'],
                'password' => '',
            ]);

            $userId = $this->resolverUserIdNaResposta($resposta);

            if ($userId === null || $userId === '') {
                $recarregados = $this->extrairUsuarios($this->apiClient->loadObjects($dispositivo, 'users'));
                foreach ($recarregados as $item) {
                    if (trim((string) ($item['registration'] ?? '')) === $dadosDesejados['registration'] && isset($item['id'])) {
                        $userId = (string) $item['id'];
                        break;
                    }
                }
            }

            if ($userId === null || $userId === '') {
                throw new InvalidArgumentException('Usuário criado no dispositivo, mas não foi possível obter seu identificador para vinculação.');
            }

            $userIdsReservados[$userId] = true;

            $this->persistirMapeamentoColaborador(
                $dispositivo,
                $userId,
                $dadosDesejados['col_id'],
                $dadosDesejados['registration'],
                $dadosDesejados['nome']
            );

            $usuarios[] = [
                'user_id' => $userId,
                'registration' => $dadosDesejados['registration'],
                'nome' => $dadosDesejados['nome'],
                'foto_ok' => false,
                'image_timestamp' => 0,
                'col_id' => $dadosDesejados['col_id'],
                'colaborador_nome' => $dadosDesejados['nome'],
                'map_ativo' => true,
                'status_vinculo' => 'vinculado',
            ];

            $this->garantirGrupoPadraoComCache($dispositivo, $userId, $contextoGrupos);

            $respostaFoto = $this->apiClient->setUserImage($dispositivo, (int) $userId, $fotoBinaria);
            $this->validarRespostaCadastroFacial($respostaFoto);

            $ultimoIndice = count($usuarios) - 1;
            $usuarios[$ultimoIndice]['foto_ok'] = true;
            $usuarios[$ultimoIndice]['image_timestamp'] = time();

            return [
                'acao' => 'criados',
                'foto_enviada' => true,
                'pendente_foto' => false,
                'escrita_hardware' => true,
                'duplicados_removidos' => 0,
            ];
        }

        $userId = (string) $usuarioExistente['user_id'];
        $userIdsReservados[$userId] = true;

        $precisaModificarDados = trim((string) ($usuarioExistente['registration'] ?? '')) !== $dadosDesejados['registration']
            || (string) ($usuarioExistente['nome'] ?? '') !== $dadosDesejados['nome'];

        $estavaVinculado = (int) ($usuarioExistente['col_id'] ?? 0) === $dadosDesejados['col_id']
            && !empty($usuarioExistente['map_ativo']);

        $precisaEnviarFoto = $temFotoNoHarpia
            && $this->deveEnviarFotoParaUsuarioExistente($colaborador, $usuarioExistente);

        if ($precisaModificarDados) {
            $this->apiClient->modifyUser($dispositivo, (int) $userId, [
                'name' => (string) $dadosDesejados['nome'],
                'registration' => (string) $dadosDesejados['registration'],
            ]);
        }

        $this->persistirMapeamentoColaborador(
            $dispositivo,
            $userId,
            $dadosDesejados['col_id'],
            $dadosDesejados['registration'],
            $dadosDesejados['nome']
        );

        $duplicadosRemovidos = $this->removerClonesDuplicadosDoColaborador(
            $dispositivo,
            $dadosDesejados['col_id'],
            $userId,
            $usuarios,
            $userIdsReservados
        );
        $duplicadosRemovidosOut = $duplicadosRemovidos;

        $grupoCriado = $this->garantirGrupoPadraoComCache($dispositivo, $userId, $contextoGrupos);

        $this->atualizarUsuarioEmMemoria($usuarios, $userId, [
            'registration' => $dadosDesejados['registration'],
            'nome' => $dadosDesejados['nome'],
            'col_id' => $dadosDesejados['col_id'],
            'colaborador_nome' => $dadosDesejados['nome'],
            'map_ativo' => true,
            'status_vinculo' => 'vinculado',
        ]);

        if ($precisaEnviarFoto) {
            $fotoBinaria = $this->obterFotoBinariaDoColaborador($colaborador);
            $respostaFoto = $this->apiClient->setUserImage($dispositivo, (int) $userId, $fotoBinaria);
            $this->validarRespostaCadastroFacial($respostaFoto);

            $this->atualizarUsuarioEmMemoria($usuarios, $userId, [
                'foto_ok' => true,
                'image_timestamp' => time(),
            ]);
        }

        $alterou = $precisaModificarDados || !$estavaVinculado || $precisaEnviarFoto || $grupoCriado || $duplicadosRemovidos > 0;

        return [
            'acao' => $alterou ? 'atualizados' : 'inalterados',
            'foto_enviada' => $precisaEnviarFoto,
            'pendente_foto' => !$temFotoNoHarpia && empty($usuarioExistente['foto_ok']),
            'escrita_hardware' => $precisaModificarDados || $precisaEnviarFoto || $grupoCriado,
            'duplicados_removidos' => $duplicadosRemovidos,
        ];
    }

    private function deveEnviarFotoParaUsuarioExistente($colaborador, array $usuarioExistente): bool
    {
        if (empty($usuarioExistente['foto_ok'])) {
            return true;
        }

        $timestampDispositivo = (int) ($usuarioExistente['image_timestamp'] ?? 0);

        if ($timestampDispositivo <= 0) {
            return false;
        }

        $fotoAnexo = $colaborador->relationLoaded('foto_facial') ? $colaborador->foto_facial : null;

        if ($fotoAnexo && $fotoAnexo->updated_at instanceof \DateTimeInterface) {
            return $fotoAnexo->updated_at->getTimestamp() > $timestampDispositivo;
        }

        return false;
    }

    private function atualizarUsuarioEmMemoria(array &$usuarios, string $userId, array $alteracoes): void
    {
        foreach ($usuarios as &$item) {
            if ((string) ($item['user_id'] ?? '') === $userId) {
                $item = array_merge($item, $alteracoes);
                break;
            }
        }
        unset($item);
    }

    private function montarConsultaUsuarios(int $dispositivoId, array $usuarios): array
    {
        $userIds = array_values(array_filter(array_map(function (array $usuario) {
            return isset($usuario['id']) && (string) $usuario['id'] !== '' ? (string) $usuario['id'] : null;
        }, $usuarios)));

        $mapeamentos = $this->mapeamentoRepository->listarPorDispositivoEUsuarios($dispositivoId, $userIds);
        $usuariosCombinados = $this->combinarUsuariosComMapeamentos($usuarios, $mapeamentos->toArray());

        return [
            'usuarios' => $usuariosCombinados,
            'resumo' => $this->resumirUsuarios($usuariosCombinados),
        ];
    }

    private function desativarMapeamentosAusentes(int $dispositivoId, array $userIdsAtivos): void
    {
        $query = MapeamentoDispositivo::where('map_dis_id', $dispositivoId)
            ->where(function ($q) {
                $q->where('map_ativo', true)->orWhereNotNull('map_col_id');
            });

        if (!empty($userIdsAtivos)) {
            $query->whereNotIn('map_user_id', $userIdsAtivos);
        }

        $ausentes = $query->pluck('map_user_id')->all();

        if (empty($ausentes)) {
            return;
        }

        $updateQuery = MapeamentoDispositivo::where('map_dis_id', $dispositivoId);

        if (!empty($userIdsAtivos)) {
            $updateQuery->whereNotIn('map_user_id', $userIdsAtivos);
        }

        $updateQuery->update([
            'map_ativo' => false,
            'map_col_id' => null,
        ]);

        foreach ($ausentes as $userIdAusente) {
            Cache::forget(sprintf('matching:%d:%s', $dispositivoId, (string) $userIdAusente));
        }
    }

    private function reconciliarMapeamentosComUsuariosLidos(
        DispositivoAcesso $dispositivo,
        array $usuarios,
        ?array $indiceColaboradores = null
    ): void {
        if ($indiceColaboradores === null) {
            $indiceColaboradores = $this->construirIndiceColaboradores(
                $this->colaboradorRepository->listarAtivosParaExportacao()
            );
        }

        $mapeamentosExistentes = MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
            ->get()
            ->keyBy(fn (MapeamentoDispositivo $m) => (string) $m->map_user_id);

        $colIdsFortesNoDispositivo = [];
        foreach ($usuarios as $usuario) {
            $uid = isset($usuario['id']) ? (string) $usuario['id'] : '';
            if ($uid === '') {
                continue;
            }

            $map = $mapeamentosExistentes->get($uid);
            if ($map && $map->map_col_id && isset($indiceColaboradores['por_id'][(int) $map->map_col_id])) {
                $colIdsFortesNoDispositivo[(int) $map->map_col_id] = true;
            }

            $reg = trim((string) ($usuario['registration'] ?? ''));
            if ($reg !== '' && ctype_digit($reg) && (int) $reg > 0 && isset($indiceColaboradores['por_id'][(int) $reg])) {
                $colIdsFortesNoDispositivo[(int) $reg] = true;
            }
        }

        DB::transaction(function () use ($dispositivo, $usuarios, $indiceColaboradores, $mapeamentosExistentes, $colIdsFortesNoDispositivo) {
            $userIds = [];

            foreach ($usuarios as $usuario) {
                $userId = isset($usuario['id']) ? (string) $usuario['id'] : null;

                if ($userId === null || $userId === '') {
                    continue;
                }

                $userIds[] = $userId;
            }

            $this->desativarMapeamentosAusentes((int) $dispositivo->dis_id, $userIds);

            foreach ($usuarios as $usuario) {
                $userId = isset($usuario['id']) ? (string) $usuario['id'] : null;

                if ($userId === null || $userId === '') {
                    continue;
                }

                $mapeamentoExistente = $mapeamentosExistentes->get($userId);
                $colaborador = $this->resolverColaboradorNoIndice(
                    $mapeamentoExistente,
                    $usuario,
                    $indiceColaboradores,
                    $colIdsFortesNoDispositivo
                );

                $novoColId = $colaborador ? (int) $colaborador->col_id : null;
                $registration = (string) ($usuario['registration'] ?? '');
                $nomeDispositivo = (string) ($usuario['name'] ?? '');

                if ($novoColId !== null) {
                    MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
                        ->where('map_user_id', '<>', $userId)
                        ->where('map_col_id', $novoColId)
                        ->update(['map_col_id' => null]);

                    foreach ($mapeamentosExistentes as $outroUserId => $outroMap) {
                        if ((string) $outroUserId !== $userId && (int) ($outroMap->map_col_id ?? 0) === $novoColId) {
                            $outroMap->map_col_id = null;
                        }
                    }
                }

                if ($mapeamentoExistente) {
                    $alterou = (
                        ($mapeamentoExistente->map_col_id !== null ? (int) $mapeamentoExistente->map_col_id : null) !== $novoColId
                        || (string) $mapeamentoExistente->map_registration !== $registration
                        || (string) ($mapeamentoExistente->map_nome_dispositivo ?? '') !== $nomeDispositivo
                        || !(bool) $mapeamentoExistente->map_ativo
                    );

                    if ($alterou) {
                        $mapeamentoExistente->fill([
                            'map_col_id' => $novoColId,
                            'map_registration' => $registration,
                            'map_nome_dispositivo' => $nomeDispositivo,
                            'map_ativo' => true,
                        ])->save();
                    }
                } else {
                    $criado = MapeamentoDispositivo::updateOrCreate(
                        [
                            'map_dis_id' => $dispositivo->dis_id,
                            'map_user_id' => $userId,
                        ],
                        [
                            'map_col_id' => $novoColId,
                            'map_registration' => $registration,
                            'map_nome_dispositivo' => $nomeDispositivo,
                            'map_ativo' => true,
                        ]
                    );
                    $mapeamentosExistentes->put($userId, $criado);
                }

                Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));
            }
        });
    }

    private function persistirMapeamentoColaborador(
        DispositivoAcesso $dispositivo,
        string $userId,
        int $colaboradorId,
        string $registration,
        string $nomeDispositivo
    ): MapeamentoDispositivo {
        $mapeamento = DB::transaction(function () use ($dispositivo, $userId, $colaboradorId, $registration, $nomeDispositivo) {
            MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
                ->where('map_user_id', '<>', $userId)
                ->where('map_col_id', $colaboradorId)
                ->update(['map_col_id' => null]);

            $existente = MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
                ->where('map_user_id', $userId)
                ->first();

            if ($existente) {
                $alterou = (
                    (int) ($existente->map_col_id ?? 0) !== $colaboradorId
                    || (string) $existente->map_registration !== $registration
                    || (string) ($existente->map_nome_dispositivo ?? '') !== $nomeDispositivo
                    || !(bool) $existente->map_ativo
                );

                if ($alterou) {
                    $existente->fill([
                        'map_col_id' => $colaboradorId,
                        'map_registration' => $registration,
                        'map_nome_dispositivo' => $nomeDispositivo,
                        'map_ativo' => true,
                    ])->save();
                }

                return $existente;
            }

            return MapeamentoDispositivo::create([
                'map_dis_id' => $dispositivo->dis_id,
                'map_user_id' => $userId,
                'map_col_id' => $colaboradorId,
                'map_registration' => $registration,
                'map_nome_dispositivo' => $nomeDispositivo,
                'map_ativo' => true,
            ]);
        });

        Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $userId));

        return $mapeamento;
    }

    private function construirIndiceColaboradores(Collection $colaboradores): array
    {
        $porId = [];
        $contagemIdentificadores = [];
        $porIdentificadorUnico = [];
        $contagemNomes = [];
        $porNomeUnico = [];

        foreach ($colaboradores as $colaborador) {
            $colId = (int) $colaborador->col_id;
            $porId[$colId] = $colaborador;

            foreach (['col_matricula_universidade', 'col_codigo_catraca'] as $campo) {
                $valor = mb_strtolower(trim((string) ($colaborador->getAttribute($campo) ?? '')));

                if ($valor === '') {
                    continue;
                }

                $contagemIdentificadores[$valor] = ($contagemIdentificadores[$valor] ?? 0) + 1;

                if ($contagemIdentificadores[$valor] === 1) {
                    $porIdentificadorUnico[$valor] = $colaborador;
                } else {
                    unset($porIdentificadorUnico[$valor]);
                }
            }

            $nome = $this->normalizarNome((string) ($colaborador->pes_nome ?? ''));

            if ($nome !== '') {
                $contagemNomes[$nome] = ($contagemNomes[$nome] ?? 0) + 1;

                if ($contagemNomes[$nome] === 1) {
                    $porNomeUnico[$nome] = $colaborador;
                } else {
                    unset($porNomeUnico[$nome]);
                }
            }
        }

        return [
            'por_id' => $porId,
            'por_identificador_unico' => $porIdentificadorUnico,
            'por_nome_unico' => $porNomeUnico,
        ];
    }

    private function normalizarNome(string $nome): string
    {
        $limpo = trim((string) preg_replace('/\s+/u', ' ', $nome));

        if ($limpo === '') {
            return '';
        }

        return Str::lower(Str::ascii($limpo));
    }

    private function resolverColaboradorNoIndice(
        ?MapeamentoDispositivo $mapeamentoExistente,
        array $usuario,
        array $indiceColaboradores,
        array $colIdsFortesNoDispositivo
    ) {
        if ($mapeamentoExistente && $mapeamentoExistente->map_col_id) {
            $colIdExistente = (int) $mapeamentoExistente->map_col_id;

            if (isset($indiceColaboradores['por_id'][$colIdExistente])) {
                return $indiceColaboradores['por_id'][$colIdExistente];
            }
        }

        $registration = trim((string) ($usuario['registration'] ?? ''));

        if ($registration !== '' && ctype_digit($registration)) {
            $colIdPorReg = (int) $registration;

            if ($colIdPorReg > 0 && isset($indiceColaboradores['por_id'][$colIdPorReg])) {
                return $indiceColaboradores['por_id'][$colIdPorReg];
            }
        }

        if ($registration !== '') {
            $chaveId = mb_strtolower($registration);
            $porIdentificador = $indiceColaboradores['por_identificador_unico'][$chaveId] ?? null;

            if ($porIdentificador && !isset($colIdsFortesNoDispositivo[(int) $porIdentificador->col_id])) {
                return $porIdentificador;
            }
        }

        $nomeNormalizado = $this->normalizarNome((string) ($usuario['name'] ?? $usuario['nome'] ?? ''));

        if ($nomeNormalizado !== '') {
            $porNome = $indiceColaboradores['por_nome_unico'][$nomeNormalizado] ?? null;

            if ($porNome && !isset($colIdsFortesNoDispositivo[(int) $porNome->col_id])) {
                return $porNome;
            }
        }

        return null;
    }

    private function registrationPertenceAOutroColaborador(string $registration, int $colaboradorId, array $indiceColaboradores): bool
    {
        $registration = trim($registration);

        if ($registration === '') {
            return false;
        }

        if (ctype_digit($registration)) {
            $idNumerico = (int) $registration;

            if ($idNumerico > 0 && isset($indiceColaboradores['por_id'][$idNumerico]) && $idNumerico !== $colaboradorId) {
                return true;
            }
        }

        $chave = mb_strtolower($registration);
        $donoIdentificador = $indiceColaboradores['por_identificador_unico'][$chave] ?? null;

        return $donoIdentificador !== null && (int) $donoIdentificador->col_id !== $colaboradorId;
    }

    private function localizarUsuarioDoColaboradorParaSincronizacao(
        array $usuarios,
        $colaborador,
        array $userIdsReservados,
        array $indiceColaboradores
    ): ?array {
        $colaboradorId = (int) $colaborador->col_id;
        $registrationColId = (string) $colaboradorId;

        // 1 e 2. Candidatos fortes: vínculo ativo explícito OU registration correspondente ao col_id
        // Quando há duplicados no aparelho, escolhe o melhor candidato (priorizando foto válida e vínculo ativo).
        $candidatosFortes = [];
        foreach ($usuarios as $usuario) {
            $uid = (string) ($usuario['user_id'] ?? '');
            if ($uid === '' || isset($userIdsReservados[$uid])) {
                continue;
            }

            $colIdUsuario = !empty($usuario['col_id']) ? (int) $usuario['col_id'] : null;

            if ($colIdUsuario !== null && $colIdUsuario !== $colaboradorId) {
                continue;
            }

            $reg = trim((string) ($usuario['registration'] ?? ''));
            $mesmoReg = ($reg === $registrationColId)
                || ($reg !== '' && ctype_digit($reg) && (int) $reg === $colaboradorId);

            if ($colIdUsuario === $colaboradorId || $mesmoReg) {
                $candidatosFortes[] = $usuario;
            }
        }

        if (!empty($candidatosFortes)) {
            return $this->escolherMelhorUsuarioCandidato($candidatosFortes, $colaboradorId);
        }

        // 3. Registration igual a matricula universidade ou codigo da catraca (quando unico e sem colisao com outro col_id)
        foreach (['col_matricula_universidade', 'col_codigo_catraca'] as $campo) {
            $identificador = mb_strtolower(trim((string) ($colaborador->getAttribute($campo) ?? '')));

            if ($identificador === '') {
                continue;
            }

            $dono = $indiceColaboradores['por_identificador_unico'][$identificador] ?? null;
            if (!$dono || (int) $dono->col_id !== $colaboradorId) {
                continue;
            }

            $candidatosIdentificador = [];
            foreach ($usuarios as $usuario) {
                $uid = (string) ($usuario['user_id'] ?? '');
                if ($uid === '' || isset($userIdsReservados[$uid]) || !empty($usuario['col_id'])) {
                    continue;
                }

                $reg = trim((string) ($usuario['registration'] ?? ''));
                if ($reg === '' || $this->registrationPertenceAOutroColaborador($reg, $colaboradorId, $indiceColaboradores)) {
                    continue;
                }

                if (mb_strtolower($reg) === $identificador) {
                    $candidatosIdentificador[] = $usuario;
                }
            }

            if (!empty($candidatosIdentificador)) {
                return $this->escolherMelhorUsuarioCandidato($candidatosIdentificador, $colaboradorId);
            }
        }

        // 4. Nome completo normalizado inequívoco (quando único entre os colaboradores ativos)
        $nomeDesejado = $this->normalizarNome((string) ($colaborador->pes_nome ?? ''));
        if ($nomeDesejado !== '') {
            $donoNome = $indiceColaboradores['por_nome_unico'][$nomeDesejado] ?? null;

            if ($donoNome && (int) $donoNome->col_id === $colaboradorId) {
                $candidatosNome = [];

                foreach ($usuarios as $usuario) {
                    $uid = (string) ($usuario['user_id'] ?? '');
                    if ($uid === '' || isset($userIdsReservados[$uid]) || !empty($usuario['col_id'])) {
                        continue;
                    }

                    $reg = trim((string) ($usuario['registration'] ?? ''));
                    if ($this->registrationPertenceAOutroColaborador($reg, $colaboradorId, $indiceColaboradores)) {
                        continue;
                    }

                    if ($this->normalizarNome((string) ($usuario['nome'] ?? '')) === $nomeDesejado) {
                        $candidatosNome[] = $usuario;
                    }
                }

                if (count($candidatosNome) === 1) {
                    return $candidatosNome[0];
                }
            }
        }

        return null;
    }

    private function escolherMelhorUsuarioCandidato(array $candidatos, int $colaboradorId): array
    {
        if (count($candidatos) === 1) {
            return $candidatos[0];
        }

        $registrationExato = (string) $colaboradorId;

        usort($candidatos, function (array $a, array $b) use ($colaboradorId, $registrationExato) {
            $fotoA = !empty($a['foto_ok']) ? 1 : 0;
            $fotoB = !empty($b['foto_ok']) ? 1 : 0;
            if ($fotoA !== $fotoB) {
                return $fotoB <=> $fotoA;
            }

            $vinculoA = ((int) ($a['col_id'] ?? 0) === $colaboradorId && !empty($a['map_ativo'])) ? 1 : 0;
            $vinculoB = ((int) ($b['col_id'] ?? 0) === $colaboradorId && !empty($b['map_ativo'])) ? 1 : 0;
            if ($vinculoA !== $vinculoB) {
                return $vinculoB <=> $vinculoA;
            }

            $regA = trim((string) ($a['registration'] ?? '')) === $registrationExato ? 1 : 0;
            $regB = trim((string) ($b['registration'] ?? '')) === $registrationExato ? 1 : 0;
            if ($regA !== $regB) {
                return $regB <=> $regA;
            }

            return ((int) ($a['user_id'] ?? PHP_INT_MAX)) <=> ((int) ($b['user_id'] ?? PHP_INT_MAX));
        });

        return $candidatos[0];
    }

    private function buscarUsuarioRecenteNoHardware(
        DispositivoAcesso $dispositivo,
        int $colaboradorId,
        string $registrationDesejado,
        array &$usuarios,
        array $userIdsReservados
    ): ?array {
        $resposta = $this->apiClient->loadObjects($dispositivo, 'users', [
            'where' => [
                'users' => [
                    'registration' => $registrationDesejado,
                ],
            ],
        ]);

        $recentesBrutos = $this->extrairUsuarios($resposta);
        if (empty($recentesBrutos)) {
            return null;
        }

        $porIdExistente = [];
        foreach ($usuarios as $idx => $u) {
            $uid = (string) ($u['user_id'] ?? '');
            if ($uid !== '') {
                $porIdExistente[$uid] = $idx;
            }
        }

        $candidatos = [];
        foreach ($recentesBrutos as $item) {
            $uid = isset($item['id']) ? (string) $item['id'] : '';
            if ($uid === '' || isset($userIdsReservados[$uid])) {
                continue;
            }

            $reg = trim((string) ($item['registration'] ?? ''));
            if ($reg !== $registrationDesejado && !($reg !== '' && ctype_digit($reg) && (int) $reg === $colaboradorId)) {
                continue;
            }

            $existenteMemoria = isset($porIdExistente[$uid]) ? $usuarios[$porIdExistente[$uid]] : [];
            if (!empty($existenteMemoria['col_id']) && (int) $existenteMemoria['col_id'] !== $colaboradorId) {
                continue;
            }

            $fotoOk = !empty($item['image_timestamp']) || !empty($item['has_image']) || !empty($item['image']);
            $normalizado = [
                'user_id' => $uid,
                'registration' => (string) ($item['registration'] ?? ''),
                'nome' => (string) ($item['name'] ?? ''),
                'foto_ok' => $fotoOk,
                'image_timestamp' => isset($item['image_timestamp']) ? (int) $item['image_timestamp'] : 0,
                'col_id' => $existenteMemoria['col_id'] ?? null,
                'colaborador_nome' => $existenteMemoria['colaborador_nome'] ?? null,
                'map_ativo' => (bool) ($existenteMemoria['map_ativo'] ?? true),
                'status_vinculo' => $existenteMemoria['status_vinculo'] ?? 'pendente',
            ];

            if (isset($porIdExistente[$uid])) {
                $usuarios[$porIdExistente[$uid]] = $normalizado;
            } else {
                $usuarios[] = $normalizado;
                $porIdExistente[$uid] = count($usuarios) - 1;
            }

            $candidatos[] = $normalizado;
        }

        if (empty($candidatos)) {
            return null;
        }

        return $this->escolherMelhorUsuarioCandidato($candidatos, $colaboradorId);
    }

    private function removerClonesDuplicadosDoColaborador(
        DispositivoAcesso $dispositivo,
        int $colaboradorId,
        string $userIdCanonica,
        array &$usuarios,
        array $userIdsReservados
    ): int {
        $registrationColId = (string) $colaboradorId;
        $removidos = 0;

        foreach ($usuarios as $idx => $item) {
            $outroUid = (string) ($item['user_id'] ?? '');

            if ($outroUid === '' || $outroUid === $userIdCanonica || isset($userIdsReservados[$outroUid])) {
                continue;
            }

            if (!empty($item['col_id']) && (int) $item['col_id'] !== $colaboradorId) {
                continue;
            }

            $reg = trim((string) ($item['registration'] ?? ''));
            $mesmoColIdPorReg = ($reg === $registrationColId)
                || ($reg !== '' && ctype_digit($reg) && (int) $reg === $colaboradorId);

            if (!$mesmoColIdPorReg && (int) ($item['col_id'] ?? 0) !== $colaboradorId) {
                continue;
            }

            $this->apiClient->destroyUser($dispositivo, (int) $outroUid);

            MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
                ->where('map_user_id', $outroUid)
                ->update([
                    'map_ativo' => false,
                    'map_col_id' => null,
                ]);

            Cache::forget(sprintf('matching:%d:%s', $dispositivo->dis_id, $outroUid));

            unset($usuarios[$idx]);
            $removidos++;
        }

        if ($removidos > 0) {
            $usuarios = array_values($usuarios);
        }

        return $removidos;
    }

    private function executarComLockDispositivo(
        DispositivoAcesso $dispositivo,
        callable $callback,
        int $esperaSegundos = 15
    ) {
        $disId = (int) ($dispositivo->dis_id ?? 0);

        if ($disId <= 0 || !empty($this->locksDispositivoAtivos[$disId]) || Cache::getFacadeRoot() === null) {
            return $callback();
        }

        $lock = Cache::lock('sync_dispositivo_hw:' . $disId, 120);

        try {
            $adquirido = $esperaSegundos > 0 ? $lock->block($esperaSegundos) : $lock->get();
        } catch (LockTimeoutException $e) {
            $adquirido = false;
        }

        if (!$adquirido) {
            throw new LockTimeoutException(sprintf(
                'O dispositivo "%s" já está com outra sincronização em andamento. Aguarde alguns segundos e tente novamente.',
                $dispositivo->dis_nome ?: ('#' . $disId)
            ));
        }

        $this->locksDispositivoAtivos[$disId] = true;

        try {
            return $callback();
        } finally {
            unset($this->locksDispositivoAtivos[$disId]);
            optional($lock)->release();
        }
    }

    private function identificarColaboradorParaRelatorio($colaborador): array
    {
        $colId = (int) $colaborador->col_id;
        $registration = (string) $colId;
        $nome = trim((string) ($colaborador->pes_nome ?? ''));

        if ($nome === '' && $colaborador->relationLoaded('pessoa') && $colaborador->pessoa) {
            $nome = trim((string) $colaborador->pessoa->pes_nome);
        } elseif ($nome === '' && !empty($colaborador->col_pes_id)) {
            $nome = trim((string) optional($colaborador->pessoa)->pes_nome);
        }

        $descricao = $nome !== ''
            ? sprintf('%s (matrícula %s)', $nome, $registration)
            : sprintf('Colaborador #%d', $colId);

        return [
            'col_id' => $colId,
            'nome' => $nome !== '' ? $nome : sprintf('Colaborador #%d', $colId),
            'registration' => $registration,
            'descricao' => $descricao,
        ];
    }

    public function extrairMensagemErroColaborador(\Throwable $exception): string
    {
        if ($exception instanceof BadResponseException) {
            $body = (string) $exception->getResponse()->getBody();
            $decoded = json_decode($body, true);

            if (is_array($decoded)) {
                $erros = $decoded['errors'] ?? [];
                $primeiro = is_array($erros) ? reset($erros) : null;

                if (is_array($primeiro) && !empty($primeiro['message'])) {
                    return 'O dispositivo rejeitou a operação: ' . $this->traduzirMensagemDispositivo((string) $primeiro['message']);
                }

                if (!empty($decoded['error']) && is_string($decoded['error'])) {
                    return 'Erro retornado pelo dispositivo: ' . $this->traduzirMensagemDispositivo($decoded['error']);
                }
            }
        }

        return (string) preg_replace('/([?&]session=)[^&\s]+/i', '$1***', $exception->getMessage());
    }

    public function agruparFalhasColaboradores(array $falhas): array
    {
        $agrupadas = [];

        foreach ($falhas as $falha) {
            $chave = $falha['col_id'] . '|' . $falha['mensagem'];

            if (!isset($agrupadas[$chave])) {
                $agrupadas[$chave] = [
                    'col_id' => $falha['col_id'],
                    'nome' => $falha['nome'],
                    'registration' => $falha['registration'],
                    'colaborador' => $falha['colaborador'],
                    'dispositivos' => [],
                    'mensagem' => $falha['mensagem'],
                ];
            }

            if (!in_array($falha['dispositivo'], $agrupadas[$chave]['dispositivos'], true)) {
                $agrupadas[$chave]['dispositivos'][] = $falha['dispositivo'];
            }
        }

        return array_values($agrupadas);
    }

    private function localizarUsuarioDoColaborador(array $usuarios, int $colaboradorId): ?array
    {
        $registration = (string) $colaboradorId;
        $candidatos = [];

        foreach ($usuarios as $usuario) {
            if (!empty($usuario['col_id']) && (int) $usuario['col_id'] !== $colaboradorId) {
                continue;
            }

            if ((int) ($usuario['col_id'] ?? 0) === $colaboradorId) {
                $candidatos[] = $usuario;
                continue;
            }

            $reg = trim((string) ($usuario['registration'] ?? ''));

            if ($reg === $registration || ($reg !== '' && ctype_digit($reg) && (int) $reg === $colaboradorId)) {
                $candidatos[] = $usuario;
            }
        }

        if (empty($candidatos)) {
            return null;
        }

        return $this->escolherMelhorUsuarioCandidato($candidatos, $colaboradorId);
    }

    private function buscarColaboradorParaDispositivo(int $colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->buscarAtivoComPessoa($colaboradorId);

        if (!$colaborador || !$colaborador->pessoa) {
            throw new InvalidArgumentException('Colaborador ativo não encontrado para sincronização com o dispositivo.');
        }

        return $colaborador;
    }

    private function montarDadosDispositivoDoColaborador($colaborador): array
    {
        return [
            'nome' => (string) $colaborador->pes_nome ?: (string) $colaborador->pessoa->pes_nome,
            'registration' => (string) $colaborador->col_id,
            'col_id' => (int) $colaborador->col_id,
        ];
    }

    private function obterFotoBinariaDoColaborador($colaborador): string
    {
        $anexoId = (int) ($colaborador->col_foto_anx_id ?? 0);

        if ($anexoId === 0) {
            throw new InvalidArgumentException('O colaborador selecionado não possui foto facial cadastrada.');
        }

        if (isset($this->cacheFotosBinarias[$anexoId])) {
            return $this->cacheFotosBinarias[$anexoId];
        }

        $conteudo = $this->anexoRepository->lerConteudoAnexo($anexoId);

        if ($conteudo === 'error_non_existent') {
            throw new InvalidArgumentException('A foto facial vinculada ao colaborador não foi encontrada.');
        }

        if (count($this->cacheFotosBinarias) >= 32) {
            array_shift($this->cacheFotosBinarias);
        }

        $this->cacheFotosBinarias[$anexoId] = $conteudo;

        return $conteudo;
    }

    private function localizarUsuarioPorRegistrations(array $usuarios, array $registrations): ?array
    {
        foreach ($usuarios as $usuario) {
            $registration = (string) ($usuario['registration'] ?? '');

            if ($registration !== '' && in_array($registration, $registrations, true)) {
                return $usuario;
            }
        }

        return null;
    }

    private function inicializarContextoGrupos(): array
    {
        return [
            'carregado' => false,
            'grupo_padrao_id' => null,
            'grupos_por_usuario' => [],
        ];
    }

    private function garantirGrupoPadraoComCache(DispositivoAcesso $dispositivo, string $userId, array &$contextoGrupos): bool
    {
        if (!$contextoGrupos['carregado']) {
            $contextoGrupos['grupo_padrao_id'] = $this->resolverGrupoPadraoId($dispositivo);
            $contextoGrupos['grupos_por_usuario'] = [];

            if ($contextoGrupos['grupo_padrao_id'] !== null) {
                $vinculos = $this->extrairUsuarios($this->apiClient->loadObjects($dispositivo, 'user_groups'));

                foreach ($vinculos as $vinculo) {
                    $uid = (int) ($vinculo['user_id'] ?? 0);
                    $gid = (int) ($vinculo['group_id'] ?? 0);

                    if ($uid > 0 && $gid > 0) {
                        $contextoGrupos['grupos_por_usuario'][$uid][$gid] = true;
                    }
                }
            }

            $contextoGrupos['carregado'] = true;
        }

        $groupId = $contextoGrupos['grupo_padrao_id'];

        if ($groupId === null) {
            Log::channel('ponto')->warning('Nenhum grupo padrão foi encontrado no dispositivo para vincular o usuário criado.', [
                'dispositivo_id' => $dispositivo->dis_id,
                'user_id' => $userId,
            ]);

            return false;
        }

        $uidInt = (int) $userId;

        if (!empty($contextoGrupos['grupos_por_usuario'][$uidInt][$groupId])) {
            return false;
        }

        $this->apiClient->createUserGroup($dispositivo, $uidInt, $groupId);
        $contextoGrupos['grupos_por_usuario'][$uidInt][$groupId] = true;

        return true;
    }

    private function garantirGrupoPadraoDoUsuario(DispositivoAcesso $dispositivo, string $userId): void
    {
        $groupId = $this->resolverGrupoPadraoId($dispositivo);

        if ($groupId === null) {
            Log::channel('ponto')->warning('Nenhum grupo padrão foi encontrado no dispositivo para vincular o usuário criado.', [
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

        $nomesPadrao = ['padrao', 'standard'];

        foreach ($grupos as $grupo) {
            $nome = Str::lower(Str::ascii(trim((string) ($grupo['name'] ?? ''))));

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

    public function sincronizarUsuariosEntreDispositivos(DispositivoAcesso $origem, DispositivoAcesso $destino): array
    {
        return $this->executarComLockDispositivo($destino, function () use ($origem, $destino) {
            $usuariosOrigem = $this->consultar($origem)['usuarios'];

            $usuariosDestino = [];
            foreach ($this->consultar($destino)['usuarios'] as $usuario) {
                $reg = (string) ($usuario['registration'] ?? '');
                if ($reg !== '') {
                    $usuariosDestino[$reg] = $usuario;
                }
            }

            $resultado = [
                'origem' => $origem->dis_nome,
                'destino' => $destino->dis_nome,
                'criados' => 0,
                'atualizados' => 0,
                'inalterados' => 0,
                'erros' => 0,
            ];

            foreach ($usuariosOrigem as $usuario) {
                $registration = (string) ($usuario['registration'] ?? '');
                $userIdOrigem = (int) ($usuario['user_id'] ?? 0);

                // Pula usuarios sem registration E sem user_id valido
                if ($registration === '' && $userIdOrigem === 0) {
                    continue;
                }

                // Matching em 3 niveis:
                // 1. Por registration (se preenchido na origem)
                // 2. Por registration = user_id da origem (sync anterior de usuario sem registration)
                // 3. Por user_id numerico (raro, apenas se dispositivos compartilham IDs)
                $existeNoDestino = null;
                if ($registration !== '') {
                    $existeNoDestino = $usuariosDestino[$registration] ?? null;
                }
                if (!$existeNoDestino && $userIdOrigem > 0) {
                    $existeNoDestino = $usuariosDestino[(string) $userIdOrigem] ?? null;
                }
                if (!$existeNoDestino && $userIdOrigem > 0) {
                    foreach ($usuariosDestino as $dest) {
                        if ((int) ($dest['user_id'] ?? 0) === $userIdOrigem) {
                            $existeNoDestino = $dest;
                            break;
                        }
                    }
                }

                try {
                    if (!$existeNoDestino) {
                        $nome = (string) ($usuario['nome'] ?? '');

                        if ($nome === '') {
                            $resultado['erros']++;
                            continue;
                        }

                        $regFinal = $registration !== '' ? $registration : (string) $userIdOrigem;
                        $payload = [
                            'registration' => $regFinal,
                            'name' => $nome,
                            'password' => '',
                        ];

                        $resposta = $this->apiClient->createUser($destino, $payload);
                        $novoUserId = $this->resolverUserIdNaResposta($resposta);

                        if ($novoUserId !== null) {
                            $this->garantirGrupoPadraoDoUsuario($destino, $novoUserId);
                        }

                        $fotoCopiada = false;
                        // Sincronizar foto se o usuario de origem tem
                        if (!empty($usuario['foto_ok']) && $novoUserId !== null) {
                            try {
                                $fotoBinaria = $this->apiClient->getUserImage($origem, $userIdOrigem);
                                $this->apiClient->setUserImage($destino, (int) $novoUserId, $fotoBinaria);
                                $fotoCopiada = true;
                            } catch (\Throwable $fotoException) {
                                // Foto nao bloqueia a criacao do usuario
                            }
                        }

                        $usuariosDestino[$regFinal] = [
                            'user_id' => $novoUserId ?? '',
                            'registration' => $regFinal,
                            'nome' => $nome,
                            'foto_ok' => $fotoCopiada,
                        ];

                        $resultado['criados']++;
                    } else {
                        $origemNome = trim((string) ($usuario['nome'] ?? ''));
                        $destinoNome = trim((string) ($existeNoDestino['nome'] ?? ''));
                        $destinoUserId = (int) ($existeNoDestino['user_id'] ?? 0);

                        $precisaAtualizar = false;

                        if ($origemNome !== '' && $origemNome !== $destinoNome) {
                            $this->apiClient->modifyUser($destino, $destinoUserId, [
                                'name' => $origemNome,
                            ]);

                            $precisaAtualizar = true;
                        }

                        if ($registration !== '' && $registration !== (string) ($existeNoDestino['registration'] ?? '')) {
                            $this->apiClient->modifyUser($destino, $destinoUserId, [
                                'registration' => $registration,
                            ]);

                            $precisaAtualizar = true;
                        }

                        // Sincronizar foto se origem tem e destino nao
                        if (!empty($usuario['foto_ok']) && empty($existeNoDestino['foto_ok']) && $destinoUserId > 0) {
                            try {
                                $fotoBinaria = $this->apiClient->getUserImage($origem, $userIdOrigem);
                                $this->apiClient->setUserImage($destino, $destinoUserId, $fotoBinaria);
                                $chaveDestino = $registration !== '' ? $registration : (string) $userIdOrigem;
                                if (isset($usuariosDestino[$chaveDestino])) {
                                    $usuariosDestino[$chaveDestino]['foto_ok'] = true;
                                }
                                $precisaAtualizar = true;
                            } catch (\Throwable $fotoException) {
                                // Foto nao bloqueia a atualizacao
                            }
                        }

                        if ($precisaAtualizar) {
                            $resultado['atualizados']++;
                        } else {
                            $resultado['inalterados']++;
                        }
                    }
                } catch (\Throwable $exception) {
                    $resultado['erros']++;
                }
            }

            $this->sincronizar($destino);

            return $resultado;
        });
    }

    public function sincronizarUsuariosEntreDispositivosEmLote(DispositivoAcesso $origem, iterable $destinos): array
    {
        $resultado = [
            'origem' => $origem->dis_nome,
            'dispositivos' => [],
            'erros' => [],
            'resumo' => [
                'dispositivos' => 0,
                'criados' => 0,
                'atualizados' => 0,
                'inalterados' => 0,
            ],
        ];

        foreach ($destinos as $destino) {
            if ($destino->dis_id === $origem->dis_id) {
                continue;
            }

            try {
                $item = $this->sincronizarUsuariosEntreDispositivos($origem, $destino);
                $resultado['dispositivos'][] = $item;
                $resultado['resumo']['dispositivos']++;
                $resultado['resumo']['criados'] += $item['criados'];
                $resultado['resumo']['atualizados'] += $item['atualizados'];
                $resultado['resumo']['inalterados'] += $item['inalterados'];
            } catch (\Throwable $exception) {
                $resultado['erros'][] = [
                    'dispositivo' => $destino->dis_nome,
                    'mensagem' => $exception->getMessage(),
                ];
            }
        }

        return $resultado;
    }
}
