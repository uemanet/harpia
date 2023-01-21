<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\HoraTrabalhadaDiaria;
use DB;

class HoraTrabalhadaDiariaRepository extends BaseRepository
{
    public function __construct(HoraTrabalhadaDiaria $horaTrabalhada)
    {
        $this->model = $horaTrabalhada;
    }

    public function importarDadosDeHorasTrabalhadas(array $dados)
    {
        $maiorData = $dados[0][2];
        $menorData = $dados[0][2];

        foreach ($dados as $dado) {
            $colaborador = DB::table('reh_colaboradores')
                ->where('col_codigo_catraca', '=', $dado[0])
                ->first();

            if ($colaborador) {
                HoraTrabalhadaDiaria::updateOrCreate(
                    [
                        'htd_col_id' => $colaborador->col_id,
                        'htd_data' => $dado[2],
                    ], [
                    'htd_col_id' => $colaborador->col_id,
                    'htd_horas' => $dado[1],
                    'htd_data' => $dado[2],
                ]);
                if(strtotime($dado[2]) > strtotime($maiorData)){
                    $maiorData = $dado[2];
                }
                if(strtotime($dado[2]) < strtotime($menorData)){
                    $menorData = $dado[2];
                }
            }
        }

        return ['menorData' => $menorData, 'maiorData' => $maiorData];
    }

    public function buscarDadosParaRelatorioDeHorasTrabalhadas(int $periodoLaboralId, $setorId = null)
    {
        $result = DB::table('reh_horas_trabalhadas')
            ->where('htr_pel_id', $periodoLaboralId)
            ->join('reh_colaboradores', 'col_id', '=', 'htr_col_id')
            ->join('gra_pessoas', 'col_pes_id', '=', 'pes_id');

        if($setorId){
            $result = DB::table('reh_horas_trabalhadas')
                ->join('reh_colaboradores', 'col_id', '=', 'htr_col_id')
                ->join('gra_pessoas', 'col_pes_id', '=', 'pes_id')
                ->join('reh_colaboradores_funcoes', 'col_id', '=', 'cfn_col_id')
                ->where('cfn_set_id', $setorId)
                ->where('htr_pel_id', $periodoLaboralId);
        }

        return $result->groupBy('pes_id')->orderBy('pes_nome', 'ASC')->get();
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model;

        if (!empty($search)) {
            foreach ($search as $key => $value) {

                if ($value['field'] == 'pel_inicio') {
                    $result = $result->where('htd_data', '>=', $value['term']);
                    continue;
                }

                if ($value['field'] == 'pel_termino') {
                    $result = $result->where('htd_data', '<=', $value['term']);
                    continue;
                }

                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }

    public function paginateRequest(array $requestParameters = [])
    {
        $sort = [];
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
        }

        $searchable = $this->model->searchable();
        $search = [];
        foreach ($requestParameters as $key => $value) {
            if (array_key_exists($key, $searchable) and !empty($value)) {
                $search[] = [
                    'field' => $key,
                    'type' => $searchable[$key],
                    'term' => $value
                ];
            }
        }
        return $this->paginate($sort, $search);
    }

}