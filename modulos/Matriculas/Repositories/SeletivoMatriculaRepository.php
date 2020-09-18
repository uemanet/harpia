<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;
use Modulos\Matriculas\Models\Inscricao;
use Modulos\Matriculas\Models\SeletivoMatricula;

class SeletivoMatriculaRepository extends BaseRepository
{
    public function __construct(SeletivoMatricula $seletivoMatricula)
    {
        parent::__construct($seletivoMatricula);
    }

    public function paginateInscricoes($id, $sort = null, $search = null)
    {
        $result = $this->model->join('seletivos_users', function ($join) {
            $join->on('seletivos_users.id', '=', 'seletivos_matriculas.seletivo_user_id');
        });

        $result = $result->where('seletivos_matriculas.matriculado', 1);

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $value['field'] = ($value['field'] == 'chamada_id') ? 'seletivos_matriculas.chamada_id' : $value['field'];
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

        if (empty($sort)) {
            $result = $result->orderBy('id', 'DESC');
        }

        $result = $result->select('seletivos_matriculas.*', 'seletivos_users.nome', 'seletivos_users.email', 'seletivos_users.cpf');

        return $result->paginate(15);
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
        $chamadaId = null;
        foreach ($requestParameters as $key => $value) {
            if (array_key_exists($key, $searchable) and !empty($value)) {
                $search[] = [
                    'field' => $key,
                    'type' => $searchable[$key],
                    'term' => $value
                ];
            }

            if ($key == 'chamada_id') {
                $chamadaId = $value;
            }
        }

        return $this->paginateInscricoes($chamadaId, $sort, $search);
    }
}