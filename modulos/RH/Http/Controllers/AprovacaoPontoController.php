<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\JornadaRemota;
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
            flash()->error('Usuário não autenticado.');
            return redirect()->route('rh.index.index');
        }

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$aprovador) {
            flash()->error('Colaborador não encontrado.');
            return redirect()->route('rh.index.index');
        }

        $colIds = $this->aprovadorService->listarColaboradorIdsAprovaveis($aprovador);

        $query = JornadaRemota::with([
            'colaborador.pessoa',
            'eventoEntrada',
            'eventoSaida',
            'aprovador.pessoa',
            'aprovacoes.aprovador.pessoa',
        ])
            ->whereIn('jor_col_id', $colIds)
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('jor_eva_entrada_id')
                        ->whereNotNull('jor_eva_saida_id');
                })->orWhere('jor_status', 'inconsistente');
            });

        if ($request->filled('status') && $request->status !== 'todos') {
            $query->where('jor_status', $request->status);
        } elseif (!$request->filled('status')) {
            $query->where('jor_status', 'pendente');
        }

        if ($request->filled('pes_nome')) {
            $query->whereHas('colaborador.pessoa', function ($q) use ($request) {
                $q->where('pes_nome', 'like', '%' . $request->pes_nome . '%');
            });
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('jor_data_referencia', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('jor_data_referencia', '<=', $request->data_fim);
        }

        $jornadas = $query
            ->orderBy('jor_data_referencia', 'desc')
            ->orderBy('jor_entrada_em', 'desc')
            ->paginate(15);

        return view('RH::aprovacoes_ponto.index', compact('jornadas'));
    }

    public function getShow($id)
    {
        $jornada = JornadaRemota::with([
            'colaborador.pessoa',
            'eventoEntrada',
            'eventoSaida',
            'aprovador.pessoa',
            'aprovacoes.aprovador.pessoa',
        ])->findOrFail($id);

        return view('RH::aprovacoes_ponto.show', compact('jornada'));
    }

    public function postAprovar(Request $request)
    {
        $jornada = JornadaRemota::with('colaborador')->findOrFail($request->id);
        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuário não autenticado.');
            return redirect()->route('rh.aprovacoesponto.index');
        }

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$aprovador) {
            flash()->error('Aprovador não encontrado.');
            return redirect()->route('rh.aprovacoesponto.index');
        }

        try {
            $this->aprovacaoService->aprovar($jornada, $aprovador);
            flash()->success('Registro aprovado com sucesso.');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.aprovacoesponto.index');
    }

    public function postParcial(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'horas_aceitas' => 'required|string',
            'motivo' => 'required|string|min:5|max:5000',
        ]);

        $jornada = JornadaRemota::with('colaborador')->findOrFail($request->id);
        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuário não autenticado.');
            return redirect()->route('rh.aprovacoesponto.index');
        }

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$aprovador) {
            flash()->error('Aprovador não encontrado.');
            return redirect()->route('rh.aprovacoesponto.index');
        }

        try {
            $this->aprovacaoService->aprovarParcial(
                $jornada,
                $aprovador,
                (string) $request->horas_aceitas,
                (string) $request->motivo
            );
            flash()->success('Registro aprovado parcialmente.');
        } catch (\Throwable $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.aprovacoesponto.index');
    }

    public function postReprovar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'motivo' => 'required|string|min:5|max:5000',
        ]);

        $jornada = JornadaRemota::with('colaborador')->findOrFail($request->id);
        $user = auth()->user();

        if (!$user || !$user->pessoa) {
            flash()->error('Usuário não autenticado.');
            return redirect()->route('rh.aprovacoesponto.index');
        }

        $aprovador = Colaborador::where('col_pes_id', $user->pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$aprovador) {
            flash()->error('Aprovador não encontrado.');
            return redirect()->route('rh.aprovacoesponto.index');
        }

        try {
            $this->aprovacaoService->reprovar($jornada, $aprovador, (string) $request->motivo);
            flash()->success('Registro reprovado.');
        } catch (\Throwable $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('rh.aprovacoesponto.index');
    }
}
