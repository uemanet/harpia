<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\EventoAcesso;
use DB;

class RegistrosPontoController extends BaseController
{
    public function getIndex(Request $request)
    {
        $query = EventoAcesso::query()
            ->join('reh_colaboradores', 'eva_col_id', '=', 'col_id')
            ->join('gra_pessoas', 'col_pes_id', '=', 'pes_id')
            ->leftJoin('reh_colaboradores_funcoes', function ($join) {
                $join->on('col_id', '=', 'cfn_col_id')->whereNull('cfn_data_fim');
            })
            ->leftJoin('reh_setores', 'cfn_set_id', '=', 'set_id')
            ->whereIn('eva_status', ['processado', 'aprovado', 'pendente'])
            ->select(
                'eva_col_id',
                'pes_nome',
                'set_descricao',
                DB::raw('DATE(eva_data_hora) as data'),
                DB::raw("MIN(CASE WHEN eva_tipo = 'entrada' THEN eva_data_hora END) as primeira_entrada"),
                DB::raw("MAX(CASE WHEN eva_tipo = 'saida' THEN eva_data_hora END) as ultima_saida"),
                DB::raw("eva_origem"),
                DB::raw("GROUP_CONCAT(DISTINCT eva_status) as statuses"),
                DB::raw('COUNT(*) as total_eventos')
            )
            ->groupBy('eva_col_id', 'data', 'pes_nome', 'set_descricao', 'eva_origem');

        if ($request->filled('setor')) {
            $query->where('cfn_set_id', $request->setor);
        }

        if ($request->filled('col_id')) {
            $query->where('eva_col_id', $request->col_id);
        }

        if ($request->filled('status')) {
            $query->havingRaw("GROUP_CONCAT(DISTINCT eva_status) LIKE ?", ["%{$request->status}%"]);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('eva_data_hora', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('eva_data_hora', '<=', $request->data_fim);
        }

        $registros = $query->orderBy('data', 'desc')->paginate(15);

        return view('RH::registros_ponto.index', compact('registros'));
    }

    public function getDetalhes(Request $request)
    {
        $colId = $request->get('col_id');
        $data = $request->get('data');

        $eventos = EventoAcesso::with(['colaborador.pessoa'])
            ->where('eva_col_id', $colId)
            ->whereDate('eva_data_hora', $data)
            ->orderBy('eva_data_hora')
            ->get();

        return view('RH::registros_ponto.detalhes', compact('eventos', 'colId', 'data'));
    }
}
