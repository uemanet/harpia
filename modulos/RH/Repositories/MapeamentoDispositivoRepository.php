<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Collection;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\MapeamentoDispositivo;

class MapeamentoDispositivoRepository extends BaseRepository
{
    public function __construct(MapeamentoDispositivo $mapeamentoDispositivo)
    {
        $this->model = $mapeamentoDispositivo;
    }

    public function buscarPorUserId(int $disId, string $userId): ?MapeamentoDispositivo
    {
        return $this->model
            ->where('map_dis_id', $disId)
            ->where('map_user_id', $userId)
            ->where('map_ativo', true)
            ->first();
    }

    public function buscarPorColId(int $disId, int $colId): ?MapeamentoDispositivo
    {
        return $this->model
            ->where('map_dis_id', $disId)
            ->where('map_col_id', $colId)
            ->where('map_ativo', true)
            ->first();
    }

    public function listarNaoVinculados(int $disId): array
    {
        return $this->model
            ->where('map_dis_id', $disId)
            ->whereNull('map_col_id')
            ->get()
            ->toArray();
    }

    public function buscarPorDispositivoEUsuario(int $dispositivoId, string $userId): ?MapeamentoDispositivo
    {
        return $this->model
            ->where('map_dis_id', $dispositivoId)
            ->where('map_user_id', $userId)
            ->first();
    }

    public function buscarPorDispositivoEColaborador(int $dispositivoId, int $colaboradorId, ?string $ignorarUserId = null): ?MapeamentoDispositivo
    {
        $query = $this->model
            ->where('map_dis_id', $dispositivoId)
            ->where('map_col_id', $colaboradorId)
            ->where('map_ativo', true);

        if ($ignorarUserId !== null) {
            $query->where('map_user_id', '<>', $ignorarUserId);
        }

        return $query->first();
    }

    public function listarPorDispositivoEUsuarios(int $dispositivoId, array $userIds): Collection
    {
        $query = $this->model
            ->leftJoin('reh_colaboradores', 'reh_mapeamento_dispositivo.map_col_id', '=', 'reh_colaboradores.col_id')
            ->leftJoin('gra_pessoas', 'reh_colaboradores.col_pes_id', '=', 'gra_pessoas.pes_id')
            ->select([
                'reh_mapeamento_dispositivo.*',
                'gra_pessoas.pes_nome as colaborador_nome',
            ])
            ->where('reh_mapeamento_dispositivo.map_dis_id', $dispositivoId);

        if (!empty($userIds)) {
            $query->whereIn('reh_mapeamento_dispositivo.map_user_id', $userIds);
        }

        return $query->get();
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
            ->leftJoin('reh_colaboradores', function ($join) {
                $join->on('map_col_id', '=', 'col_id');
            })
            ->leftJoin('gra_pessoas', function ($join) {
                $join->on('reh_colaboradores.col_pes_id', '=', 'gra_pessoas.pes_id');
            })
            ->join('reh_dispositivos_acesso', function ($join) {
                $join->on('map_dis_id', '=', 'dis_id');
            })
            ->select('reh_mapeamento_dispositivo.*', 'gra_pessoas.pes_nome', 'reh_dispositivos_acesso.dis_nome');

        if (!empty($search)) {
            foreach ($search as $value) {
                switch ($value['field']) {
                    case 'pes_nome':
                        $result = $result->where('gra_pessoas.pes_nome', $value['type'], "%{$value['term']}%");
                        break;
                    case 'dis_nome':
                        $result = $result->where('reh_dispositivos_acesso.dis_nome', $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        if ($value['type'] === 'like') {
                            $result = $result->where('reh_mapeamento_dispositivo.' . $value['field'], $value['type'], "%{$value['term']}%");
                        } else {
                            $result = $result->where('reh_mapeamento_dispositivo.' . $value['field'], $value['type'], $value['term']);
                        }
                }
            }
        }

        if (!empty($sort)) {
            if ($sort['field'] === 'pes_nome') {
                $result = $result->orderBy('gra_pessoas.pes_nome', $sort['sort']);
            } else {
                $result = $result->orderBy('reh_mapeamento_dispositivo.' . $sort['field'], $sort['sort']);
            }
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
        $searchable['pes_nome'] = 'like';
        $searchable['dis_nome'] = 'like';

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
