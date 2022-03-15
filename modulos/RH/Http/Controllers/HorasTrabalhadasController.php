<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\PeriodoLaboral;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;
use Illuminate\Http\Request;

class HorasTrabalhadasController extends BaseController
{
    protected $horaTrabalhadaRepository;

    public function __construct(HoraTrabalhadaRepository $horaTrabalhadaRepository)
    {
        $this->horaTrabalhadaRepository = $horaTrabalhadaRepository;
    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->horaTrabalhadaRepository->paginateRequest($request->all());

        if ($tableData->count() and isset($request->all()['htr_pel_id'])) {
            $tabela = $tableData->columns(array(
                'htr_id' => '#',
                'htr_col_id' => 'Colaborador',
                'htr_horas_previstas' => 'Horas Previstas',
                'htr_horas_trabalhadas' => 'Horas Trabalhadas',
                'htr_horas_justificadas' => 'Horas Justificadas',
                'htr_saldo' => 'Saldo',
            ))
                ->modify('htr_col_id', function ($obj) {
                    return $obj->colaborador->pessoa->pes_nome;
                })->sortable(array('htr_id'));

            $paginacao = $tableData->appends($request->except('page'));

        }

        $periodosLaborais = PeriodoLaboral::all()->sortBy('pel_inicio')->pluck('pel_inicio', 'pel_id');

        return view('RH::horastrabalhadas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'periodosLaborais' => $periodosLaborais]);
    }
}
