<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Services\PontoRemotoService;

class PontoRemotoController extends BaseController
{
    public function __construct(
        private PontoRemotoService $pontoRemotoService
    ) {
    }

    public function getIndex()
    {
        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuario nao autenticado.');
            return redirect()->route('login');
        }

        $colaborador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$colaborador) {
            flash()->error('Colaborador nao encontrado.');
            return redirect()->route('rh.index.index');
        }

        $estado = $this->pontoRemotoService->obterEstadoAtualDoDia($colaborador);
        $meusRegistros = $this->pontoRemotoService->listarMeusRegistros($colaborador->col_id);

        return view('RH::ponto_remoto.index', compact('colaborador', 'estado', 'meusRegistros'));
    }

    public function postEntrada(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuario nao autenticado.');
            return redirect()->back();
        }

        $colaborador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$colaborador) {
            flash()->error('Colaborador nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->pontoRemotoService->registrar($colaborador, 'entrada', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'canal' => 'web',
            ]);

            flash()->success('Entrada registrada com sucesso. Aguardando aprovacao do gestor.');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.pontoremoto.index');
    }

    public function postSaida(Request $request)
    {
        $request->validate([
            'atividades' => 'required|string|min:5|max:5000',
        ]);

        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuario nao autenticado.');
            return redirect()->back();
        }

        $colaborador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$colaborador) {
            flash()->error('Colaborador nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->pontoRemotoService->registrar($colaborador, 'saida', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'canal' => 'web',
                'atividades' => (string) $request->atividades,
            ]);

            flash()->success('Saida registrada com sucesso. Aguardando aprovacao do gestor.');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.pontoremoto.index');
    }
}
