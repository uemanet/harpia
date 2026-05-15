<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Services\AprovacaoPontoService;
use Modulos\RH\Services\AprovadorPontoService;

class AprovacaoPontoController extends BaseController
{
    public function __construct(
        private AprovacaoPontoService $aprovacaoService,
        private AprovadorPontoService $aprovadorService
    ) {
    }

    public function getIndex(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuario nao autenticado.');
            return redirect()->route('rh.index.index');
        }

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$aprovador) {
            flash()->error('Colaborador nao encontrado.');
            return redirect()->route('rh.index.index');
        }

        $colIds = $this->aprovadorService->listarColaboradorIdsAprovaveis($aprovador);

        $pendentes = EventoAcesso::with(['colaborador.pessoa'])
            ->whereIn('eva_col_id', $colIds)
            ->where('eva_origem', 'home_office')
            ->where('eva_status', 'pendente')
            ->orderBy('eva_data_hora', 'desc')
            ->paginate(15);

        return view('RH::aprovacoes_ponto.index', compact('pendentes'));
    }

    public function getShow($id)
    {
        $evento = EventoAcesso::with(['colaborador.pessoa', 'aprovacoes'])->findOrFail($id);

        return view('RH::aprovacoes_ponto.show', compact('evento'));
    }

    public function postAprovar(Request $request)
    {
        $evento = EventoAcesso::findOrFail($request->id);
        $user = auth()->user();

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        try {
            $this->aprovacaoService->aprovar($evento, $aprovador);
            flash()->success('Registro aprovado com sucesso.');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.aprovacoesponto.index');
    }

    public function postReprovar(Request $request)
    {
        $evento = EventoAcesso::findOrFail($request->id);
        $user = auth()->user();

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        try {
            $this->aprovacaoService->reprovar($evento, $aprovador, $request->motivo);
            flash()->success('Registro reprovado.');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.aprovacoesponto.index');
    }
}
