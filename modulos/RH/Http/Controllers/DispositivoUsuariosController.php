<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\RH\Http\Requests\DispositivoUsuarioRequest;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\DispositivoAcessoRepository;
use Modulos\RH\Services\ExportacaoUsuariosDispositivoService;
use Modulos\RH\Services\SincronizacaoUsuariosDispositivoService;

class DispositivoUsuariosController extends BaseController
{
    public function __construct(
        private DispositivoAcessoRepository $dispositivoRepository,
        private ColaboradorRepository $colaboradorRepository,
        private AnexoRepository $anexoRepository,
        private SincronizacaoUsuariosDispositivoService $sincronizacaoService,
        private ExportacaoUsuariosDispositivoService $exportacaoService
    ) {
    }

    public function getIndex(Request $request): View
    {
        $dispositivos = $this->dispositivoRepository->listarAtivosParaSelecao();
        $colaboradores = $this->colaboradorRepository->listarAtivosParaSelecao();
        $dispositivo = null;
        $usuarios = [];
        $resumo = null;

        if ($request->filled('dis_id')) {
            $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

            if (!$dispositivo) {
                flash()->error('Dispositivo ativo não encontrado.');
                return view('RH::dispositivo_usuarios.index', compact('dispositivos', 'colaboradores', 'dispositivo', 'usuarios', 'resumo'));
            }

            try {
                $consulta = $this->sincronizacaoService->consultar($dispositivo);
                $usuarios = $consulta['usuarios'];
                $resumo = $consulta['resumo'];
            } catch (\Throwable $exception) {
                $this->tratarExcecao($exception, 'Não foi possível consultar os usuários do dispositivo informado.');
            }
        }

        return view('RH::dispositivo_usuarios.index', compact('dispositivos', 'colaboradores', 'dispositivo', 'usuarios', 'resumo'));
    }

    public function getEdit($userId, Request $request)
    {
        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
        ]);

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo não encontrado.');
            return redirect()->route('rh.dispositivousuarios.index');
        }

        try {
            $usuario = $this->sincronizacaoService->buscarUsuario($dispositivo, (string) $userId);

            if (!$usuario) {
                flash()->error('Usuário do dispositivo não encontrado.');
                return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
            }
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível consultar o usuário informado no dispositivo.');
            return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
        }

        $colaboradores = $this->colaboradorRepository->listarAtivosParaSelecao();

        return view('RH::dispositivo_usuarios.edit', compact('dispositivo', 'usuario', 'colaboradores'));
    }

    public function postCreate(DispositivoUsuarioRequest $request)
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo não encontrado.');
            return redirect()->back()->withInput($request->all());
        }

        try {
            $dispositivosDestino = $this->resolverDispositivosAlvo($request, (int) $dispositivo->dis_id);
            $resultado = $this->sincronizacaoService->garantirUsuarioEmDispositivos(
                $dispositivosDestino,
                (int) $request->get('col_id')
            );

            $this->flashResultadoCadastroLote($resultado);
            return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível cadastrar o usuário no dispositivo.');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function putEdit($userId, DispositivoUsuarioRequest $request)
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo não encontrado.');
            return redirect()->back()->withInput($request->all());
        }

        try {
            $aplicarTodos = $request->boolean('aplicar_todos');
            $colaboradorId = (int) $request->get('col_id');
            $foto = $request->file('foto');

            if ($foto) {
                $this->atualizarFotoColaborador($colaboradorId, $foto);
            }

            if ($aplicarTodos) {
                $resultado = $this->sincronizacaoService->atualizarUsuarioEmTodosDispositivos(
                    $dispositivo,
                    (string) $userId,
                    $colaboradorId
                );

                if ($resultado['atualizados'] > 0) {
                    flash()->success(sprintf(
                        'Usuário atualizado em %d dispositivo(s): %s.',
                        $resultado['atualizados'],
                        implode(', ', $resultado['dispositivos'])
                    ));
                }

                if ($resultado['erros'] > 0) {
                    $msg = sprintf('Falha ao atualizar em %d dispositivo(s).', $resultado['erros']);
                    if (!empty($resultado['detalhes_erros'])) {
                        $msg .= ' ' . implode('; ', $resultado['detalhes_erros']);
                    }
                    flash()->error($msg);
                }
            } else {
                $this->sincronizacaoService->atualizarUsuario(
                    $dispositivo,
                    (string) $userId,
                    $colaboradorId
                );

                flash()->success('Usuário do dispositivo atualizado com sucesso.');
            }

            return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível atualizar o usuário do dispositivo.');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function postDelete(Request $request)
    {
        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'user_id' => 'required|string|max:20',
            'todos_dispositivos' => 'nullable|in:0,1',
        ]);

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo não encontrado.');
            return redirect()->back();
        }

        try {
            if ($request->boolean('todos_dispositivos')) {
                $resultado = $this->sincronizacaoService->removerUsuarioDeTodosDispositivos(
                    $dispositivo,
                    (string) $request->get('user_id')
                );

                if ($resultado['removidos'] > 0) {
                    flash()->success(sprintf(
                        'Usuário removido de %d dispositivo(s): %s.',
                        $resultado['removidos'],
                        implode(', ', $resultado['dispositivos'])
                    ));
                }

                if ($resultado['erros'] > 0) {
                    flash()->error(sprintf(
                        'Falha ao remover de %d dispositivo(s).',
                        $resultado['erros']
                    ));
                }
            } else {
                $this->sincronizacaoService->removerUsuario($dispositivo, (string) $request->get('user_id'));
                flash()->success('Usuário removido do dispositivo com sucesso.');
            }
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível remover o usuário do dispositivo.');
        }

        return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
    }

    public function postSincronizar(Request $request)
    {
        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
        ]);

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo não encontrado.');
            return redirect()->back();
        }

        try {
            $resultado = $this->sincronizacaoService->sincronizar($dispositivo);
            flash()->success(sprintf(
                'Sincronização concluída: %d usuários, %d vinculados, %d pendentes.',
                $resultado['resumo']['total'],
                $resultado['resumo']['vinculados'],
                $resultado['resumo']['pendentes']
            ));
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível sincronizar os usuários do dispositivo.');
        }

        return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
    }

    public function postSincronizarTodos(Request $request)
    {
        $acaoLote = $request->get('acao_lote');

        if ($acaoLote === 'processar') {
            $this->validate($request, [
                'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
                'acao_lote' => 'required|in:processar',
                'token' => 'required|string|max:100',
            ]);

            return $this->processarLoteSincronizacaoTodos((string) $request->get('token'));
        }

        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'dispositivos_destino' => 'nullable|array',
            'dispositivos_destino.*' => 'integer|exists:reh_dispositivos_acesso,dis_id',
            'sincronizar_todos_dispositivos' => 'nullable|boolean',
            'acao_lote' => 'nullable|in:iniciar,processar',
        ]);

        if ($acaoLote === 'iniciar') {
            return $this->iniciarLoteSincronizacaoTodos($request);
        }

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo não encontrado.');
            return redirect()->back();
        }

        @set_time_limit(0);

        $falhasColaboradores = [];

        try {
            $dispositivosDestino = $this->resolverDispositivosAlvo($request, (int) $dispositivo->dis_id, 'sincronizar_todos_dispositivos');
            $resultado = $this->sincronizacaoService->sincronizarColaboradoresEmDispositivos($dispositivosDestino);
            $falhasColaboradores = $resultado['falhas_colaboradores'] ?? [];

            $this->registrarFlashResultadoSincronizacaoTodos($resultado);
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível sincronizar os usuários nos dispositivos selecionados.');
        }

        $redirect = redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);

        if (!empty($falhasColaboradores)) {
            $redirect->with('falhas_sincronizacao', $falhasColaboradores);
        }

        return $redirect;
    }

    public function getExportarCsv($dispositivoId = null)
    {
        $dispositivo = null;

        if ($dispositivoId) {
            $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $dispositivoId);

            if (!$dispositivo) {
                flash()->error('Dispositivo ativo não encontrado.');
                return redirect()->back();
            }
        }

        try {
            $conteudo = $this->exportacaoService->gerarCsv($dispositivo);
            $nomeArquivo = $this->exportacaoService->nomeArquivo($dispositivo);

            return response($conteudo, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
            ]);
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Não foi possível gerar o CSV para o dispositivo informado.');
            return redirect()->back();
        }
    }

    private function tratarExcecao(\Throwable $exception, string $mensagemPadrao): void
    {
        if (config('app.debug')) {
            throw $exception;
        }

        flash()->error($exception instanceof \InvalidArgumentException ? $exception->getMessage() : $mensagemPadrao);
    }

    private function resolverDispositivosAlvo(
        Request $request,
        int $dispositivoPadraoId,
        string $campoTodos = 'cadastrar_em_todos_dispositivos'
    ): Collection {
        if ($request->boolean($campoTodos)) {
            $dispositivos = $this->dispositivoRepository->listarAtivos();
        } else {
            $ids = array_filter((array) $request->get('dispositivos_destino', []));

            if (empty($ids)) {
                $ids = [$dispositivoPadraoId];
            }

            $dispositivos = $this->dispositivoRepository->listarAtivosPorIds($ids);
        }

        if ($dispositivos->isEmpty()) {
            throw new \InvalidArgumentException('Selecione ao menos um dispositivo ativo para executar a operação.');
        }

        return $dispositivos;
    }

    private function flashResultadoCadastroLote(array $resultado): void
    {
        $sucessos = $resultado['criados'] + $resultado['atualizados'];

        if ($sucessos > 0) {
            flash()->success(sprintf(
                'Cadastro concluído em %d dispositivo(s): %d criado(s) e %d atualizado(s).',
                $resultado['total'],
                $resultado['criados'],
                $resultado['atualizados']
            ));
        }

        if (!empty($resultado['erros'])) {
            flash()->error($this->montarMensagemErrosLote($resultado['erros']));
        }
    }

    private function iniciarLoteSincronizacaoTodos(Request $request): JsonResponse
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            return response()->json([
                'erro' => 'Dispositivo ativo não encontrado.',
            ], 404);
        }

        try {
            $dispositivosDestino = $this->resolverDispositivosAlvo(
                $request,
                (int) $dispositivo->dis_id,
                'sincronizar_todos_dispositivos'
            );
        } catch (\InvalidArgumentException $exception) {
            return response()->json([
                'erro' => $exception->getMessage(),
            ], 422);
        }

        $totalColaboradores = $this->colaboradorRepository->listarAtivosParaExportacao()->count();
        $dispositivosLista = $dispositivosDestino->values()->map(function ($item) {
            return [
                'dis_id' => (int) $item->dis_id,
                'dis_nome' => (string) $item->dis_nome,
                'offset' => 0,
            ];
        })->all();

        $token = (string) Str::uuid();
        $estado = [
            'token' => $token,
            'user_id' => Auth::id(),
            'dis_id_origem' => (int) $dispositivo->dis_id,
            'dispositivos' => $dispositivosLista,
            'indice_dispositivo_atual' => 0,
            'total_colaboradores' => $totalColaboradores,
            'resumo' => [
                'dispositivos' => 0,
                'criados' => 0,
                'atualizados' => 0,
                'fotos_sincronizadas' => 0,
                'pendentes_foto' => 0,
                'inalterados' => 0,
                'falhas' => 0,
            ],
            'duplicados_removidos' => 0,
            'erros' => [],
            'todas_falhas' => [],
        ];

        Cache::put($this->chaveCacheSincronizacaoLote($token), $estado, now()->addMinutes(30));

        return response()->json([
            'concluido' => false,
            'token' => $token,
            'total_dispositivos' => count($dispositivosLista),
            'indice_dispositivo_atual' => 0,
            'dispositivo_atual' => $dispositivosLista[0]['dis_nome'] ?? $dispositivo->dis_nome,
            'offset_atual' => 0,
            'total_colaboradores' => $totalColaboradores,
            'progresso_percentual' => 0,
            'duplicados_removidos' => 0,
            'resumo' => $estado['resumo'],
            'erros' => [],
        ]);
    }

    private function processarLoteSincronizacaoTodos(string $token): JsonResponse
    {
        $chaveCache = $this->chaveCacheSincronizacaoLote($token);
        $estado = Cache::get($chaveCache);

        if (!is_array($estado)) {
            return response()->json([
                'erro' => 'A sessão de sincronização em lote expirou. Inicie a sincronização novamente.',
            ], 404);
        }

        if (($estado['user_id'] ?? null) !== Auth::id()) {
            return response()->json([
                'erro' => 'Operação não autorizada para esta sessão de sincronização.',
            ], 403);
        }

        $lockToken = Cache::lock('sync_dispositivo_token:' . $token, 60);
        if (!$lockToken->get()) {
            return $this->montarRespostaProgressoLote($token, $estado, true);
        }

        try {
            $estado = Cache::get($chaveCache);
            if (!is_array($estado)) {
                return response()->json([
                    'erro' => 'A sessão de sincronização em lote expirou. Inicie a sincronização novamente.',
                ], 404);
            }

            $dispositivos = $estado['dispositivos'] ?? [];
            $totalDispositivos = count($dispositivos);
            $indice = (int) ($estado['indice_dispositivo_atual'] ?? 0);

            if ($indice < $totalDispositivos) {
                $itemAtual = $dispositivos[$indice];
                $dispositivoAlvo = $this->dispositivoRepository->buscarAtivo((int) $itemAtual['dis_id']);

                if (!$dispositivoAlvo) {
                    $estado['erros'][] = [
                        'dispositivo' => $itemAtual['dis_nome'],
                        'mensagem' => 'Dispositivo ativo não encontrado.',
                    ];
                    $estado['indice_dispositivo_atual'] = $indice + 1;
                } else {
                    try {
                        $lote = $this->sincronizacaoService->sincronizarLoteColaboradoresNoDispositivo(
                            $dispositivoAlvo,
                            (int) ($itemAtual['offset'] ?? 0),
                            5,
                            60,
                            8.0,
                            null,
                            (int) config('rh.sync_lock_wait_seconds', 3)
                        );

                        $estado['total_colaboradores'] = (int) $lote['total_colaboradores'];
                        $estado['resumo']['criados'] += $lote['criados'];
                        $estado['resumo']['atualizados'] += $lote['atualizados'];
                        $estado['resumo']['fotos_sincronizadas'] += $lote['fotos_sincronizadas'];
                        $estado['resumo']['pendentes_foto'] += $lote['pendentes_foto'];
                        $estado['resumo']['inalterados'] += $lote['inalterados'];
                        $estado['resumo']['falhas'] += count($lote['falhas']);
                        $estado['duplicados_removidos'] = (int) ($estado['duplicados_removidos'] ?? 0)
                            + (int) ($lote['duplicados_removidos'] ?? 0);

                        foreach ($lote['falhas'] as $falha) {
                            $estado['todas_falhas'][] = $falha;
                        }

                        $estado['dispositivos'][$indice]['offset'] = (int) $lote['proximo_offset'];

                        if ($lote['concluido']) {
                            $estado['resumo']['dispositivos']++;
                            $estado['indice_dispositivo_atual'] = $indice + 1;
                        }
                    } catch (LockTimeoutException $exception) {
                        Cache::put($chaveCache, $estado, now()->addMinutes(30));

                        return $this->montarRespostaProgressoLote($token, $estado, true);
                    } catch (\Throwable $exception) {
                        $estado['erros'][] = [
                            'dispositivo' => $dispositivoAlvo->dis_nome,
                            'mensagem' => $this->sincronizacaoService->extrairMensagemErroColaborador($exception),
                        ];
                        $estado['indice_dispositivo_atual'] = $indice + 1;
                    }
                }
            }

            if ((int) $estado['indice_dispositivo_atual'] >= $totalDispositivos) {
                Cache::forget($chaveCache);

                $falhasColaboradores = $this->sincronizacaoService->agruparFalhasColaboradores($estado['todas_falhas']);

                $this->registrarFlashResultadoSincronizacaoTodos([
                    'resumo' => $estado['resumo'],
                    'duplicados_removidos' => (int) ($estado['duplicados_removidos'] ?? 0),
                    'erros' => $estado['erros'],
                    'falhas_colaboradores' => $falhasColaboradores,
                ]);

                if (!empty($falhasColaboradores)) {
                    session()->flash('falhas_sincronizacao', $falhasColaboradores);
                }

                return response()->json([
                    'concluido' => true,
                    'progresso_percentual' => 100,
                    'duplicados_removidos' => (int) ($estado['duplicados_removidos'] ?? 0),
                    'resumo' => $estado['resumo'],
                    'erros' => $estado['erros'],
                    'redirect_url' => route('rh.dispositivousuarios.index', ['dis_id' => $estado['dis_id_origem']]),
                ]);
            }

            Cache::put($chaveCache, $estado, now()->addMinutes(30));

            return $this->montarRespostaProgressoLote($token, $estado, false);
        } finally {
            optional($lockToken)->release();
        }
    }

    private function montarRespostaProgressoLote(string $token, array $estado, bool $aguardandoLock): JsonResponse
    {
        $dispositivos = $estado['dispositivos'] ?? [];
        $totalDispositivos = count($dispositivos);
        $novoIndice = (int) ($estado['indice_dispositivo_atual'] ?? 0);
        $proximoItem = $dispositivos[$novoIndice] ?? null;
        $offsetAtual = (int) ($proximoItem['offset'] ?? 0);
        $totalCol = max(1, (int) ($estado['total_colaboradores'] ?? 0));
        $unidadesTotais = max(1, $totalDispositivos * $totalCol);
        $unidadesConcluidas = ($novoIndice * $totalCol) + min($offsetAtual, $totalCol);
        $percentual = min(99, (int) floor(($unidadesConcluidas / $unidadesTotais) * 100));

        return response()->json([
            'concluido' => false,
            'aguardando_lock' => $aguardandoLock,
            'token' => $token,
            'total_dispositivos' => $totalDispositivos,
            'indice_dispositivo_atual' => $novoIndice,
            'dispositivo_atual' => $proximoItem['dis_nome'] ?? '',
            'offset_atual' => $offsetAtual,
            'total_colaboradores' => (int) ($estado['total_colaboradores'] ?? 0),
            'progresso_percentual' => $percentual,
            'duplicados_removidos' => (int) ($estado['duplicados_removidos'] ?? 0),
            'resumo' => $estado['resumo'],
            'erros' => $estado['erros'] ?? [],
        ]);
    }

    private function registrarFlashResultadoSincronizacaoTodos(array $resultado): void
    {
        $falhasColaboradores = $resultado['falhas_colaboradores'] ?? [];
        $duplicadosRemovidos = (int) ($resultado['duplicados_removidos'] ?? 0);

        if (($resultado['resumo']['dispositivos'] ?? 0) > 0) {
            $sufixoDuplicados = $duplicadosRemovidos > 0
                ? sprintf(', %d duplicado(s) removido(s)', $duplicadosRemovidos)
                : '';

            $mensagemResumo = sprintf(
                'Sincronização em lote concluída em %d dispositivo(s): %d criados, %d atualizados, %d fotos sincronizadas, %d pendentes por falta de foto, %d já compatíveis%s e %d falha(s) individual(is).',
                $resultado['resumo']['dispositivos'],
                $resultado['resumo']['criados'],
                $resultado['resumo']['atualizados'],
                $resultado['resumo']['fotos_sincronizadas'],
                $resultado['resumo']['pendentes_foto'],
                $resultado['resumo']['inalterados'],
                $sufixoDuplicados,
                $resultado['resumo']['falhas']
            );

            if (($resultado['resumo']['falhas'] ?? 0) > 0) {
                flash()->warning($mensagemResumo);
            } else {
                flash()->success($mensagemResumo);
            }
        }

        if (!empty($falhasColaboradores)) {
            flash()->error(
                $this->montarMensagemFalhasColaboradores($falhasColaboradores),
                'Colaboradores com falha na sincronização',
                ['timeOut' => 15000, 'extendedTimeOut' => 5000]
            );
        }

        if (!empty($resultado['erros'])) {
            flash()->error($this->montarMensagemErrosLote($resultado['erros']));
        }
    }

    private function chaveCacheSincronizacaoLote(string $token): string
    {
        return 'sync_dispositivo_lote:' . $token;
    }

    private function montarMensagemErrosLote(array $erros): string
    {
        return 'Falhas em: ' . implode('; ', array_map(function (array $erro) {
            return $erro['dispositivo'] . ' (' . $erro['mensagem'] . ')';
        }, $erros));
    }

    private function montarMensagemFalhasColaboradores(array $falhasColaboradores): string
    {
        $limiteToast = 5;
        $itens = array_slice($falhasColaboradores, 0, $limiteToast);

        $partes = array_map(function (array $falha) {
            $dispositivos = implode(', ', $falha['dispositivos'] ?? []);

            return sprintf(
                '%s [%s]: %s',
                $falha['colaborador'],
                $dispositivos,
                $falha['mensagem']
            );
        }, $itens);

        $restantes = count($falhasColaboradores) - count($itens);

        if ($restantes > 0) {
            $partes[] = sprintf('e mais %d item(ns) — confira a lista completa na página', $restantes);
        }

        return implode('; ', $partes);
    }

    private function atualizarFotoColaborador(int $colaboradorId, $arquivo): void
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            throw new \InvalidArgumentException('Colaborador selecionado não encontrado.');
        }

        $colaborador->col_foto_anx_id = $this->salvarFotoFacial($arquivo, $colaborador->col_foto_anx_id);
        $colaborador->save();
    }

    private function salvarFotoFacial($arquivo, $anexoAtualId = null): int
    {
        $arquivo = (new \Modulos\RH\Services\ImagemFacialService())->redimensionarUpload($arquivo);

        if ($anexoAtualId) {
            $anexo = $this->anexoRepository->atualizarAnexo($anexoAtualId, $arquivo);

            if (is_array($anexo)) {
                throw new \InvalidArgumentException($anexo['message'] ?? 'Não foi possível salvar a foto facial.');
            }

            if (!$anexo) {
                throw new \InvalidArgumentException('Não foi possível salvar a foto facial.');
            }

            return (int) $anexoAtualId;
        }

        $anexo = $this->anexoRepository->salvarAnexo($arquivo);

        if (is_array($anexo)) {
            throw new \InvalidArgumentException($anexo['message'] ?? 'Não foi possível salvar a foto facial.');
        }

        if (!$anexo) {
            throw new \InvalidArgumentException('Não foi possível salvar a foto facial.');
        }

        return (int) $anexo->anx_id;
    }
}
