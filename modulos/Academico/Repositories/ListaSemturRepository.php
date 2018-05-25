<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Academico\Models\ListaSemtur;
use Modulos\Core\Repository\BaseRepository;

class ListaSemturRepository extends BaseRepository
{
    public function __construct(ListaSemtur $model)
    {
        parent::__construct($model);
    }

    public function paginateRequest(array $requestParameters = null)
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

        if (array_key_exists('lst_id', $requestParameters)) {
            return $this->paginateMatriculas($sort, $search);
        }

        return $this->paginate($sort, $search);
    }

    public function paginateMatriculas($sort = null, $search = null)
    {
        $result = $this->model
            ->join('acd_matriculas_listas_semtur', function ($join) {
                $join->on('mls_lst_id', '=', 'lst_id');
            })
            ->join('acd_matriculas', function ($join) {
                $join->on('mat_id', '=', 'mls_mat_id');
            })
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })
            ->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            });

        if (!empty($search)) {
            foreach ($search as $key => $value) {
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

    public function getTurmasByLista($id)
    {
        $result = $this->model
            ->join('acd_matriculas_listas_semtur', function ($join) {
                $join->on('mls_lst_id', '=', 'lst_id');
            })
            ->join('acd_matriculas', function ($join) {
                $join->on('mls_mat_id', '=', 'mat_id');
            })
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->where('lst_id', '=', $id)
            ->groupBy('mat_trm_id')
            ->pluck('trm_nome', 'trm_id');

        return $result;
    }

    public function getPolosByLista($id)
    {
        $result = $this->model
            ->join('acd_matriculas_listas_semtur', function ($join) {
                $join->on('mls_lst_id', '=', 'lst_id');
            })
            ->join('acd_matriculas', function ($join) {
                $join->on('mls_mat_id', '=', 'mat_id');
            })
            ->join('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->where('lst_id', '=', $id)
            ->groupBy('mat_pol_id')
            ->pluck('pol_nome', 'pol_id');

        return $result;
    }

    public function findAll(array $parameters, array $sort = [], array $select = [])
    {
        if (empty($parameters)) {
            return [];
        }

        $query = $this->model->join('acd_matriculas_listas_semtur', function ($join) {
            $join->on('mls_lst_id', '=', 'lst_id');
        })
            ->join('acd_matriculas', function ($join) {
                $join->on('mls_mat_id', '=', 'mat_id');
            })
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })
            ->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            });

        foreach ($parameters as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        if (!empty($select)) {
            $query = $query->select($select);
        }

        if (!empty($sort)) {
            foreach ($sort as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        return $query->get();
    }

    public function getMatriculasOutOfLista($listaId, $turmaId, $poloId = null)
    {
        $query = \Modulos\Academico\Models\Matricula::join('acd_turmas', function ($join) {
            $join->on('mat_trm_id', '=', 'trm_id');
        })
            ->join('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })
            ->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })
            ->select('acd_matriculas.*', 'pes_nome', 'pol_nome', 'trm_nome')
            ->whereNotIn('mat_id', function ($q) use ($listaId, $turmaId) {
                $q->select('mat_id')
                    ->from('acd_matriculas')
                    ->join('acd_matriculas_listas_semtur', 'mls_mat_id', '=', 'mat_id')
                    ->where('mls_lst_id', '=', $listaId)
                    ->where('mat_trm_id', '=', $turmaId);
            })
            ->where('mat_situacao', '=', 'cursando')
            ->where('mat_trm_id', '=', $turmaId);

        if ($poloId) {
            $query = $query->where('mat_pol_id', '=', $poloId);
        }

        $matriculas = $query->orderBy('pes_nome', 'asc')->get();

        if ($matriculas->count()) {
            foreach ($matriculas as $matricula) {
                $matricula->apto = 1;

                if (!$this->validateMatricula($matricula)) {
                    $matricula->apto = 0;
                }
            }
        }

        return $matriculas;
    }

    public function validateMatricula(\Modulos\Academico\Models\Matricula $matricula)
    {
        $pessoa = $matricula->aluno->pessoa;

        if (empty($pessoa->pes_mae)) {
            return false;
        }

        if (empty($pessoa->pes_nascimento) || $pessoa->pes_nascimento == '00/00/0000') {
            return false;
        }

        if (empty($pessoa->pes_cidade)) {
            return false;
        }

        $rg = $pessoa->documentos()->where('doc_tpd_id', 1)->first();

        if (!$rg || empty($rg->doc_conteudo) || empty($rg->doc_data_expedicao)) {
            return false;
        }

        $cpf = $pessoa->documentos()->where('doc_tpd_id', 2)->first();

        if (!$cpf || empty($cpf->doc_conteudo)) {
            return false;
        }

        return true;
    }
}
