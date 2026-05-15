<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\DispositivoAcesso;

class DispositivoAcessoRepository extends BaseRepository
{
    public function __construct(DispositivoAcesso $dispositivoAcesso)
    {
        $this->model = $dispositivoAcesso;
    }

    public function buscarAtivos()
    {
        return $this->model->where('dis_status', 'ativo')->get();
    }

    public function atualizarCursor(int $disId, int $lastLogId): void
    {
        $this->model->where('dis_id', $disId)->update([
            'dis_ultimo_access_log_id' => $lastLogId,
            'dis_ultima_coleta_em' => now(),
        ]);
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model;

        if (!empty($search)) {
            foreach ($search as $value) {
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
