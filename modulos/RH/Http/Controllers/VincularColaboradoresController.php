<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\VincularMapeamentoDispositivoRequest;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\DispositivoAcessoRepository;
use Modulos\RH\Services\SincronizacaoUsuariosDispositivoService;
use Modulos\RH\Services\ExportacaoUsuariosDispositivoService;

class VincularColaboradoresController extends BaseController
{
    public function __construct(
        private DispositivoAcessoRepository $dispositivoRepository,
        private ColaboradorRepository $colaboradorRepository,
        private SincronizacaoUsuariosDispositivoService $sincronizacaoService,
        private ExportacaoUsuariosDispositivoService $exportacaoService
    ) {
    }

    public function getIndex(Request $request)
    {
        $dispositivos = $this->dispositivoRepository->buscarAtivos();
        $colaboradores = $this->colaboradorRepository->listarAtivosParaSelecao();
        $dispositivo = null;
        $usuarios = [];
        $resumo = null;

        if ($request->filled('dis_id')) {
            $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

            if (!$dispositivo) {
                flash()->error('Dispositivo ativo nao encontrado.');
                return view('RH::vincular_colaboradores.index', compact('dispositivos', 'colaboradores', 'dispositivo', 'usuarios', 'resumo'));
            }

            try {
                $consulta = $this->sincronizacaoService->consultar($dispositivo);
                $usuarios = $this->filtrarUsuarios($consulta['usuarios'], $request);
                $resumo = $consulta['resumo'];
            } catch (\Throwable $exception) {
                $this->tratarExcecao($exception, 'Nao foi possivel consultar os usuarios do dispositivo para vinculacao.');
            }
        }

        return view('RH::vincular_colaboradores.index', compact('dispositivos', 'colaboradores', 'dispositivo', 'usuarios', 'resumo'));
    }

    public function postVincular(VincularMapeamentoDispositivoRequest $request)
    {
        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->sincronizacaoService->vincularUsuario(
                $dispositivo,
                (string) $request->get('user_id'),
                (int) $request->get('col_id')
            );

            flash()->success('Colaborador vinculado ao usuario do dispositivo com sucesso.');
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel vincular o colaborador ao usuario do dispositivo.');
        }

        return redirect()->route('rh.vincularcolaboradores.index', [
            'dis_id' => $dispositivo->dis_id,
            'status' => request()->get('status'),
            'busca' => request()->get('busca'),
        ]);
    }

    public function postDesvincular(Request $request)
    {
        $this->validate($request, [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'user_id' => 'required|string|max:20',
        ]);

        $dispositivo = $this->dispositivoRepository->buscarAtivo((int) $request->get('dis_id'));

        if (!$dispositivo) {
            flash()->error('Dispositivo ativo nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->sincronizacaoService->desvincularUsuario($dispositivo, (string) $request->get('user_id'));
            flash()->success('Usuario do dispositivo desvinculado com sucesso.');
        } catch (\Throwable $exception) {
            $this->tratarExcecao($exception, 'Nao foi possivel desvincular o usuario do dispositivo.');
        }

        return redirect()->route('rh.vincularcolaboradores.index', [
            'dis_id' => $dispositivo->dis_id,
            'status' => request()->get('status'),
            'busca' => request()->get('busca'),
        ]);
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
            $this->tratarExcecao($exception, 'Nao foi possivel sincronizar o mapeamento do dispositivo.');
        }

        return redirect()->route('rh.vincularcolaboradores.index', ['dis_id' => $dispositivo->dis_id]);
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
            $this->tratarExcecao($exception, 'Nao foi possivel gerar o CSV para vinculacao.');
            return redirect()->back();
        }
    }

    private function filtrarUsuarios(array $usuarios, Request $request): array
    {
        $status = (string) $request->get('status');
        $busca = trim((string) $request->get('busca'));

        return array_values(array_filter($usuarios, function (array $usuario) use ($status, $busca) {
            if ($status === 'vinculado' && empty($usuario['col_id'])) {
                return false;
            }

            if ($status === 'pendente' && !empty($usuario['col_id'])) {
                return false;
            }

            if ($busca === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', [
                (string) $usuario['user_id'],
                (string) $usuario['registration'],
                (string) $usuario['nome'],
                (string) ($usuario['colaborador_nome'] ?? ''),
            ]));

            return mb_strpos($haystack, mb_strtolower($busca)) !== false;
        }));
    }

    private function tratarExcecao(\Throwable $exception, string $mensagemPadrao): void
    {
        if (config('app.debug')) {
            throw $exception;
        }

        flash()->error($exception instanceof \InvalidArgumentException ? $exception->getMessage() : $mensagemPadrao);
    }
}
