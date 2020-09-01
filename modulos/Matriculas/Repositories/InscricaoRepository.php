<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;
use Modulos\Matriculas\Models\Inscricao;

class InscricaoRepository extends BaseRepository
{
    public function __construct(Inscricao $inscricao)
    {
        parent::__construct($inscricao);
    }

    public function getInscricaoBySeletivoAndUser($seletivoId, $usuarioId)
    {
        $inscricao = $this->model->where('seletivo_id', $seletivoId)->where('user_id', $usuarioId)->first();

        return $inscricao;
    }

    public function getCamposExtrasRespostasBySeletivoUserId($seletivoId, $userId)
    {
        return DB::table('inscricoes')->join('users', function ($join) {
            $join->on('users.id', '=', 'inscricoes.user_id');
        })->join('campos_extras_respostas', function ($join) {
            $join->on('users.id', '=', 'campos_extras_respostas.user_id');
        })->join('campos_extras', function ($join) {
            $join->on('campos_extras.id', '=', 'campos_extras_respostas.campo_extra_id');
        })->where('inscricoes.seletivo_id', $seletivoId)
          ->where('campos_extras.seletivo_id', $seletivoId)
          ->where('inscricoes.user_id', $userId)
          ->select('campos_extras.label','campos_extras.nome', 'campos_extras_respostas.resposta')
          ->get();
    }

    public function paginateInscricoes($id, $sort = null, $search = null)
    {
        $result = $this->model->join('users', function ($join) {
            $join->on('users.id', '=', 'inscricoes.user_id');
        });

        $result = $result->whereIn('inscricoes.status', ['classificado', 'deferido', 'completo']);

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