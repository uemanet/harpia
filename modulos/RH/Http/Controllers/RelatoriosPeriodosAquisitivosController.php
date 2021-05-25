<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\RH\Models\Funcao;
use Modulos\RH\Models\Setor;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Repositories\PeriodoAquisitivoRepository;

class RelatoriosPeriodosAquisitivosController extends BaseController
{
    protected $periodoAquisitivoRepository;
    protected $colaboradorRepository;

    public function __construct(PeriodoAquisitivoRepository $periodoAquisitivoRepository, ColaboradorRepository $colaborador)
    {
        $this->periodoAquisitivoRepository = $periodoAquisitivoRepository;
        $this->colaboradorRepository = $colaborador;
    }

    public function getIndex(Request $request)
    {

        $featureToggle = null;

        if(!$featureToggle){
            return null;
        }
        //TODO: Refactor this

        $paginacao = null;
        $tabela = null;

        $search = array_merge($request->all(), ['col_status' => 'ativo']);

        $tableData = $this->colaboradorRepository->paginateRequest($search);

        foreach ($tableData as $data){
            $currentPeriod = $this->periodoAquisitivoRepository->currentPeriod($data);
            $data->currentPeriod =  $currentPeriod ? $currentPeriod['inicio'].' a '.$currentPeriod['fim'] : null;
            $data->acquireds =  $currentPeriod['adquiridos'];
        }

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'col_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'setores_index' => 'Setor',
                'funcoes_index' => 'Função',
                'currentPeriod' => 'Período atual',
                'acquireds' => 'Adquiridos'
            ))->sortable(array('col_id', 'pes_nome', 'col_status'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $setores = Setor::all()->sortBy('set_descricao')->pluck('set_descricao', 'set_id');
        $funcoes = Funcao::all()->sortBy('fun_descricao')->pluck('fun_descricao', 'fun_id');

        return view('RH::relatorios.index',compact( 'tabela','paginacao', 'setores', 'funcoes'));
    }
}
