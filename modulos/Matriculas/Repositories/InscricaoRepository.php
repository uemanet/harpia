<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;
use Modulos\Matriculas\Models\Inscricao;
use Modulos\Matriculas\Models\SeletivoMatricula;

class InscricaoRepository extends BaseRepository
{
    public function __construct(Inscricao $inscricao)
    {
        parent::__construct($inscricao);
    }



    public function paginateInscricoes($id, $sort = null, $search = null)
    {
        $result = $this->model->join('users', function ($join) {
            $join->on('users.id', '=', 'inscricoes.user_id');
        });

        $key = array_search('seletivo_id', array_column($search, 'field'));
        $seletivoId = $search[$key]['term'];
        $seletivosMatriculas = SeletivoMatricula::whereHas('chamada', function ($query) use ($seletivoId){
          return $query->where('seletivo_id', $seletivoId);
        })->get();

        $cpf = [];
        foreach ($seletivosMatriculas as $seletivoMatricula){
            $cpf[] = $seletivoMatricula->user->cpf;
        }

        $result = $result->whereIn('inscricoes.status', ['deferido']);

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $value['field'] = ($value['field'] == 'seletivo_id') ? 'inscricoes.seletivo_id' : $value['field'];
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

        $result = $result->whereNotIn('users.cpf', $cpf);
        $result = $result->select('inscricoes.*', 'users.nome', 'users.email', 'users.cpf');

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
        $seletivoId = null;
        foreach ($requestParameters as $key => $value) {
            if (array_key_exists($key, $searchable) and !empty($value)) {
                $search[] = [
                    'field' => $key,
                    'type' => $searchable[$key],
                    'term' => $value
                ];
            }

            if ($key == 'seletivo_id') {
                $seletivoId = $value;
            }
        }

        return $this->paginateInscricoes($seletivoId, $sort, $search);
    }
}