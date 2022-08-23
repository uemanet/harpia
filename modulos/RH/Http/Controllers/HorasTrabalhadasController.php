<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\PeriodoLaboral;
use Modulos\RH\Models\Setor;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\PeriodoLaboralRepository;
use ActionButton;

class HorasTrabalhadasController extends BaseController
{
    protected $horaTrabalhadaRepository;
    protected $periodoLaboralRepository;
    protected $colaboradorRepository;

    public function __construct(HoraTrabalhadaRepository $horaTrabalhadaRepository,
                                PeriodoLaboralRepository $periodoLaboralRepository,
                                ColaboradorRepository $colaboradorRepository)
    {
        $this->horaTrabalhadaRepository = $horaTrabalhadaRepository;
        $this->periodoLaboralRepository = $periodoLaboralRepository;
        $this->colaboradorRepository = $colaboradorRepository;

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
                'htr_action' => 'Ações'
            ))
                ->modify('htr_col_id', function ($obj) {
                    return $obj->colaborador->pessoa->pes_nome;
                })
                ->modify('htr_action', function ($id) use ($request) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-plus',
                                'route' => 'rh.horastrabalhadas.justificativas.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Justificativas',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'rh.horastrabalhadas.horastrabalhadasdiariasporperiodolaboral',
                                'parameters' => [ $id->colaborador->col_id,$request->all()['htr_pel_id']],
                                'label' => 'Horas Trabalhadas',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('htr_id'));

            $paginacao = $tableData->appends($request->except('page'));

        }

        $periodosLaboraisTest = PeriodoLaboral::all()->sortBy('pel_inicio');

        $periodosLaborais = [];

        foreach ($periodosLaboraisTest as $item) {
            $periodosLaborais[$item->pel_id] = $item->pel_inicio.' a '.$item->pel_termino;
        }
        $setores = Setor::all()->sortBy('set_descricao')->pluck('set_descricao', 'set_id');

        return view('RH::horastrabalhadas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'periodosLaborais' => $periodosLaborais, 'setores' => $setores]);
    }

    public function getColaboradorHorasTrabalhadas(int $colaboradorId, Request $request)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $paginacao = null;
        $tabela = null;

        $requestData = $request->all();
        $request['htr_col_id'] = $colaboradorId;

        $tableData = $this->horaTrabalhadaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'htr_id' => '#',
                'htr_col_id' => 'Colaborador',
                'htr_periodo' => 'Periodo',
                'htr_horas_previstas' => 'Horas Previstas',
                'htr_horas_trabalhadas' => 'Horas Trabalhadas',
                'htr_horas_justificadas' => 'Horas Justificadas',
                'htr_saldo' => 'Saldo'
            ))
                ->modify('htr_col_id', function ($obj) {
                    return $obj->colaborador->pessoa->pes_nome;
                })
                ->modify('htr_periodo', function ($obj) {
                    return $obj->periodo->pel_inicio.' a '.$obj->periodo->pel_termino;
                })
                ->sortable(array('htr_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::colaboradores.horastrabalhadasdiarias', ['colaborador' => $colaborador ,'tabela' => $tabela, 'paginacao' => $paginacao]);
    }
}
