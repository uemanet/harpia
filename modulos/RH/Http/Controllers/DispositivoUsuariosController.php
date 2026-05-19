<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Modulos\Core\Http\Controller\BaseController;
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
                flash()->error('Dispositivo ativo nao encontrado.');
                return view('RH::dispositivo_usuarios.index', compact('dispositivos', 'colaboradores', 'dispositivo', 'usuarios', 'resumo'));
            }

            try {
                $consulta = $this->sincronizacaoService->consultar($dispositivo);
                $usuarios = $consulta['usuarios'];
                $resumo = $consulta['resumo'];
            } catch (\Throwable $exception) {
                $this->tratarExcecao($exception, 'Nao foi possivel consultar os usuarios do dispositivo informado.');
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
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->route('rh.dispositivousuarios.index');
        }

        try {
            $usuario = $this->sincronizacaoService->buscarUsuario($dispositivo, (string) $userId);

            if (!$usuario) {
                flash()->error('Usuario do dispositivo nao encontrado.');
                return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
            }
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel consultar o usuario informado no dispositivo.');
            return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
        }

        $colaboradores = $this->colaboradorRepository->listarAtivosParaSelecao();

        return view('RH::dispositivo_usuarios.edit', compact('dispositivo', 'usuario', 'colaboradores'));
    }

    public function postCreate(DispositivoUsuarioRequest $request)
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back()->withInput($request->all());
        }

        try {
            $dispositivosDestino = $this->resolverDispositivosAlvo($request, (int) $dispositivo->dis_id);
            $resultado = $this->sincronizacaoService->garantirUsuarioEmDispositivos(
                $dispositivosDestino,
                (int) $request->get('col_id'),
                $request->only(['nome', 'registration']),
                $request->file('foto')
            );

            $this->flashResultadoCadastroLote($resultado);
            return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel cadastrar o usuario no dispositivo.');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function putEdit($userId, DispositivoUsuarioRequest $request)
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back()->withInput($request->all());
        }

        try {
            $dados = $request->only(['nome', 'registration', 'col_id']);
            $foto = $request->file('foto');
            $aplicarTodos = $request->boolean('aplicar_todos');

            if ($aplicarTodos) {
                $fotoBinaria = $foto ? $this->sincronizacaoService->converterImagemParaBinario($foto) : null;

                $resultado = $this->sincronizacaoService->atualizarUsuarioEmTodosDispositivos(
                    $dispositivo,
                    (string) $userId,
                    $dados,
                    $fotoBinaria
                );

                if ($resultado['atualizados'] > 0) {
                    flash()->success(sprintf(
                        'Usuario atualizado em %d dispositivo(s): %s.',
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
                    $dados,
                    $foto
                );

                flash()->success('Usuario do dispositivo atualizado com sucesso.');
            }

            return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel atualizar o usuario do dispositivo.');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function postAtualizarFoto($userId, DispositivoUsuarioRequest $request)
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->sincronizacaoService->atualizarUsuario(
                $dispositivo,
                (string) $userId,
                [],
                $request->file('foto')
            );

            flash()->success('Foto facial atualizada com sucesso.');
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel atualizar a foto facial no dispositivo.');
        }

        return redirect()->route('rh.dispositivousuarios.edit', [
            'id' => $userId,
            'dis_id' => $dispositivo->dis_id,
        ]);
    }

    public function postRemoverFoto($userId, Request $request)
    {
        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
        ]);

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->sincronizacaoService->removerFotoUsuario($dispositivo, (string) $userId);
            flash()->success('Foto facial removida do dispositivo com sucesso.');
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel remover a foto facial do dispositivo.');
        }

        return redirect()->route('rh.dispositivousuarios.edit', [
            'id' => $userId,
            'dis_id' => $dispositivo->dis_id,
        ]);
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
            flash()->error('Dispositivo ativo nao encontrado.');
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
                        'Usuario removido de %d dispositivo(s): %s.',
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
                flash()->success('Usuario removido do dispositivo com sucesso.');
            }
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel remover o usuario do dispositivo.');
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
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back();
        }

        try {
            $resultado = $this->sincronizacaoService->sincronizar($dispositivo);
            flash()->success(sprintf(
                'Sincronizacao concluida: %d usuarios, %d vinculados, %d pendentes.',
                $resultado['resumo']['total'],
                $resultado['resumo']['vinculados'],
                $resultado['resumo']['pendentes']
            ));
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel sincronizar os usuarios do dispositivo.');
        }

        return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
    }

    public function postSincronizarTodos(Request $request)
    {
        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'dispositivos_destino' => 'nullable|array',
            'dispositivos_destino.*' => 'integer|exists:reh_dispositivos_acesso,dis_id',
            'sincronizar_todos_dispositivos' => 'nullable|boolean',
        ]);

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back();
        }

        try {
            $dispositivosDestino = $this->resolverDispositivosAlvo($request, (int) $dispositivo->dis_id, 'sincronizar_todos_dispositivos');
            $resultado = $this->sincronizacaoService->sincronizarColaboradoresEmDispositivos($dispositivosDestino);

            if ($resultado['resumo']['dispositivos'] > 0) {
                flash()->success(sprintf(
                    'Sincronizacao em lote concluida em %d dispositivo(s): %d criados, %d atualizados e %d ja compativeis.',
                    $resultado['resumo']['dispositivos'],
                    $resultado['resumo']['criados'],
                    $resultado['resumo']['atualizados'],
                    $resultado['resumo']['inalterados']
                ));
            }

            if (!empty($resultado['erros'])) {
                flash()->error($this->montarMensagemErrosLote($resultado['erros']));
            }
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel sincronizar os usuarios nos dispositivos selecionados.');
        }

        return redirect()->route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]);
    }

    public function getExportarCsv($dispositivoId = null)
    {
        $dispositivo = null;

        if ($dispositivoId) {
            $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $dispositivoId);

            if (!$dispositivo) {
                flash()->error('Dispositivo ativo nao encontrado.');
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
            $this->tratarExcecao($exception, 'Nao foi possivel gerar o CSV para o dispositivo informado.');
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
            throw new \InvalidArgumentException('Selecione ao menos um dispositivo ativo para executar a operacao.');
        }

        return $dispositivos;
    }

    private function flashResultadoCadastroLote(array $resultado): void
    {
        $sucessos = $resultado['criados'] + $resultado['atualizados'];

        if ($sucessos > 0) {
            flash()->success(sprintf(
                'Cadastro concluido em %d dispositivo(s): %d criado(s) e %d atualizado(s).',
                $resultado['total'],
                $resultado['criados'],
                $resultado['atualizados']
            ));
        }

        if (!empty($resultado['erros'])) {
            flash()->error($this->montarMensagemErrosLote($resultado['erros']));
        }
    }

    private function montarMensagemErrosLote(array $erros): string
    {
        return 'Falhas em: ' . implode('; ', array_map(function (array $erro) {
            return $erro['dispositivo'] . ' (' . $erro['mensagem'] . ')';
        }, $erros));
    }
}
