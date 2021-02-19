<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\ColaboradorFuncao;
use Modulos\RH\Models\Setor;
use Modulos\Seguranca\Models\Auditoria;

class ColaboradorRepository extends BaseRepository
{
    public function __construct(Colaborador $colaborador)
    {
        $this->model = $colaborador;
    }

//    /**
//     * Retorna listas de pares com dados de tabelas
//     * @param string $identifier
//     * @param string $field
//     * @param bool $all
//     * @return \Illuminate\Support\Collection
//     */
//    public function lists($identifier, $field, $all = false)
//    {
//        $sql = DB::table('gra_pessoas')
//            ->join('acd_professores', 'pes_id', '=', 'acd_professores.prf_pes_id');
//
//        if (!$all) {
//            $sql = $sql->leftJoin('acd_centros', 'cen_prf_diretor', '=', 'prf_id')
//                ->leftJoin('acd_cursos', 'crs_prf_diretor', '=', 'prf_id')
//                ->whereNull('cen_prf_diretor')
//                ->whereNull('crs_prf_diretor');
//        }
//
//        $sql = $sql->select($identifier, $field)->orderBy('pes_nome', 'asc');
//
//        $entries = $sql->pluck($field, $identifier);
//
//        return collect($entries);
//    }


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

    public function paginate($sort = null, $search = null)
    {

        $result = $this->model->join('gra_pessoas', function ($join) {
            $join->on('col_pes_id', '=', 'pes_id');
        })->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        })->leftJoin('reh_colaboradores_funcoes', function ($join) {
            $join->on('col_id', '=', 'cfn_col_id');
        })->groupBy('col_id');

        if (!empty($search)) {
            foreach ($search as $value) {

                if ($value['field'] == 'funcoes') {
                    $result = $result->whereIn('cfn_fun_id', $value['term'])->where('cfn_data_fim', null);
                    continue;
                }

                if ($value['field'] == 'cfn_set_id') {
                    $result = $result->where('cfn_set_id', $value['term'])->where('cfn_data_fim', null);
                    continue;
                }

                if ($value['field'] == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value['term']);
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

        $results = $result->paginate(15);


        foreach ($results as $result) {

            $setores = ColaboradorFuncao::join('reh_setores', 'set_id', 'cfn_set_id')
                ->where('cfn_col_id', $result->col_id)
                ->where('cfn_data_fim', null)
                ->groupBy('set_id')
                ->orderBy('set_descricao')
                ->pluck('set_descricao', 'set_id')
                ->toArray();

            $result->setores_index = implode(',', $setores);

            $funcoes = ColaboradorFuncao::join('reh_funcoes', 'fun_id', 'cfn_fun_id')
                ->where('cfn_col_id', $result->col_id)
                ->where('cfn_data_fim', null)
                ->groupBy('fun_id')
                ->orderBy('fun_descricao')
                ->pluck('fun_descricao', 'fun_id')
                ->toArray();

            $result->funcoes_index = implode(',', $funcoes);
        }

        return $results;
    }

    public function search(array $options, array $select = null)
    {
        $query = $this->model->select('reh_colaboradores.*', 'gra_pessoas.*', 'gra_documentos.*')
            ->join('gra_pessoas', function ($join) {
                $join->on('col_pes_id', '=', 'pes_id');
            })->leftJoin('gra_documentos', function ($join) {
                $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
            });

        foreach ($options as $op) {
            $query = $query->where($op[0], $op[1], $op[2]);
        }

        if (!is_null($select)) {
            $query = $query->select($select);
        }

        return $query->get();
    }

    public function getHistory($col_id)
    {

        $history = Auditoria::where('log_table_id', $col_id)
            ->where('log_table', 'reh_colaboradores')->get()->toArray();

        foreach ($history as $key => $item) {
            $history[$key]['log_object'] = json_decode($item['log_object'], true);

        }

    }


}
