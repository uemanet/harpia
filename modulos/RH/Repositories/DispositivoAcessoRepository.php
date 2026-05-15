<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Collection;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\DispositivoAcesso;

class DispositivoAcessoRepository extends BaseRepository
{
    public function __construct(DispositivoAcesso $dispositivoAcesso)
    {
        $this->model = $dispositivoAcesso;
    }

    public function listarAtivosParaSelecao()
    {
        return $this->model
            ->where('dis_status', 'ativo')
            ->orderBy('dis_nome')
            ->pluck('dis_nome', 'dis_id');
    }

    public function buscarAtivo(int $dispositivoId): ?DispositivoAcesso
    {
        return $this->model
            ->where('dis_id', $dispositivoId)
            ->where('dis_status', 'ativo')
            ->first();
    }

    public function buscarAtivos()
    {
        return $this->model->where('dis_status', 'ativo')->get();
    }

    public function listarAtivos(): Collection
    {
        return $this->model
            ->where('dis_status', 'ativo')
            ->orderBy('dis_nome')
            ->get();
    }

    public function listarAtivosPorIds(array $dispositivoIds): Collection
    {
        $dispositivoIds = array_values(array_unique(array_filter(array_map('intval', $dispositivoIds))));

        if (empty($dispositivoIds)) {
            return collect();
        }

        return $this->model
            ->where('dis_status', 'ativo')
            ->whereIn('dis_id', $dispositivoIds)
            ->orderBy('dis_nome')
            ->get();
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
