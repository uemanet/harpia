<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Aluno;
use Modulos\Core\Repository\BaseRepository;
use DB;
use Auth;

class AlunoRepository extends BaseRepository
{
    public function __construct(Aluno $aluno)
    {
        $this->model = $aluno;
    }

    /**
     * Pagina somente alunos matriculados em cursos vinculados com o usuario logado
     * @param null $sort
     * @param null $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateOnlyWithBonds($sort = null, $search = null)
    {
        $result = $this->model->select('acd_alunos.*', 'gra_pessoas.*', 'gra_documentos.*')
            ->join('acd_matriculas', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })->join('acd_usuarios_cursos', function ($join) {
                $join->on('ucr_crs_id', '=', 'crs_id');
            })->leftJoin('gra_documentos', function ($join) {
                $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
            })->where('ucr_usr_id', '=', Auth::user()->usr_id);

        if (!empty($search)) {
            foreach ($search as $value) {
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

        return $this->model->paginateWithBonds($result->get(), 15);
    }

    /**
     * Paginacao com os vinculos
     * @param null $sort
     * @param null $search
     * @return mixed
     */
    public function paginateAllWithBonds($sort = null, $search = null)
    {
        $vinculados = $this->model->select('acd_alunos.*', 'gra_pessoas.*', 'gra_documentos.*')
            ->join('acd_matriculas', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })->join('acd_usuarios_cursos', function ($join) {
                $join->on('ucr_crs_id', '=', 'crs_id');
            })->leftJoin('gra_documentos', function ($join) {
                $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
            })->where('ucr_usr_id', '=', Auth::user()->usr_id);

        $result = $this->model->select('acd_alunos.*', 'gra_pessoas.*', 'gra_documentos.*')
            ->leftJoin('acd_matriculas', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })->leftJoin('gra_documentos', function ($join) {
                $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
            })->where('mat_alu_id', '=', null);

        $result = $vinculados->union($result);

        if (!empty($search)) {
            foreach ($search as $value) {
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

        return $this->model->paginateWithBonds($result->get(), 15);
    }

    /**
     * Paginacao de todos os alunos
     * @param null $sort
     * @param null $search
     * @return mixed
     */
    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('gra_pessoas', function ($join) {
            $join->on('alu_pes_id', '=', 'pes_id');
        })->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        });

        if (!empty($search)) {
            foreach ($search as $value) {
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

        $result = $result->paginate(15);
        return $result;
    }

    /**
     * @param bool $vinculo
     * @param bool $onlyBonds
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequest(array $requestParameters = null, $vinculo = false, $onlyBonds = false)
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

        if($vinculo){
            return $this->paginateAllWithBonds($sort, $search);
        }

        if($onlyBonds){
            return $this->paginateOnlyWithBonds($sort, $search);
        }

        return $this->paginate($sort, $search);
    }


    public function findByNomeOrCpf(array $search)
    {
        $result = $this->model->join('gra_pessoas', function ($join) {
            $join->on('alu_pes_id', '=', 'pes_id');
        })->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        });

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                if ($key == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value);
                }
                if ($key == 'pes_nome') {
                    $result = $result->where($key, 'like', "%{$value}%");
                }
            }

            $result = $result->orderBy('pes_nome', 'ASC')->get();

            return $result;
        }

        return null;
    }

    /**
     * Retorna os cursos nos quais o aluno esta matriculado
     * @param $alunoId
     * @return mixed
     */
    public function getCursos($alunoId)
    {
        $result = DB::table('acd_matriculas')
                    ->select('crs_id')
                    ->join('acd_turmas', 'mat_trm_id', '=', 'trm_id')
                    ->join('acd_ofertas_cursos', 'trm_ofc_id', '=', 'ofc_id')
                    ->join('acd_cursos', 'ofc_crs_id', '=', 'crs_id')
                    ->where('mat_alu_id', '=', $alunoId)
                    ->get();

        if (!$result->isEmpty()) {
            $cursos = [];

            $result = $result->toArray();

            foreach ($result as $curso) {
                $cursos[] = $curso->crs_id;
            }

            return $cursos;
        }

        return [];
    }
}
