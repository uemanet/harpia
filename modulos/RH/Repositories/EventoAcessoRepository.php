<?php

namespace Modulos\RH\Repositories;

use Carbon\Carbon;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\EventoAcesso;

class EventoAcessoRepository extends BaseRepository
{
    public function __construct(EventoAcesso $eventoAcesso)
    {
        $this->model = $eventoAcesso;
    }

    public function buscarPorHash(string $hash): ?EventoAcesso
    {
        return $this->model->where('eva_hash', $hash)->first();
    }

    public function buscarEventosDoDia(int $colId, string $data): array
    {
        return $this->model
            ->where('eva_col_id', $colId)
            ->whereDate('eva_data_hora', $data)
            ->whereIn('eva_status', ['processado', 'aprovado'])
            ->orderBy('eva_data_hora')
            ->get()
            ->toArray();
    }

    public function buscarPrimeiroEventoDoDia(int $colId, string $data): ?EventoAcesso
    {
        return $this->model
            ->where('eva_col_id', $colId)
            ->whereDate('eva_data_hora', $data)
            ->whereIn('eva_status', ['processado', 'aprovado'])
            ->orderBy('eva_data_hora')
            ->first();
    }

    public function buscarEventosComErro(int $limit = 100): array
    {
        return $this->model
            ->where('eva_status', 'erro')
            ->orderBy('eva_data_hora')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function expurgarAntigos(int $dias): int
    {
        $dataLimite = Carbon::now()->subDays($dias);
        return $this->model->where('eva_data_hora', '<', $dataLimite)->delete();
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
            ->join('reh_colaboradores', function ($join) {
                $join->on('eva_col_id', '=', 'col_id');
            })
            ->leftJoin('gra_pessoas', function ($join) {
                $join->on('reh_colaboradores.col_pes_id', '=', 'gra_pessoas.pes_id');
            })
            ->leftJoin('reh_dispositivos_acesso', function ($join) {
                $join->on('eva_dis_id', '=', 'dis_id');
            })
            ->select('reh_eventos_acesso.*', 'gra_pessoas.pes_nome', 'reh_dispositivos_acesso.dis_nome');

        if (!empty($search)) {
            foreach ($search as $value) {
                switch ($value['field']) {
                    case 'pes_nome':
                        $result = $result->where('gra_pessoas.pes_nome', $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        if ($value['type'] === 'like') {
                            $result = $result->where('reh_eventos_acesso.' . $value['field'], $value['type'], "%{$value['term']}%");
                        } else {
                            $result = $result->where('reh_eventos_acesso.' . $value['field'], $value['type'], $value['term']);
                        }
                }
            }
        }

        if (!empty($sort)) {
            if ($sort['field'] === 'pes_nome') {
                $result = $result->orderBy('gra_pessoas.pes_nome', $sort['sort']);
            } elseif ($sort['field'] === 'dis_nome') {
                $result = $result->orderBy('reh_dispositivos_acesso.dis_nome', $sort['sort']);
            } else {
                $result = $result->orderBy('reh_eventos_acesso.' . $sort['field'], $sort['sort']);
            }
        } else {
            $result = $result->orderBy('reh_eventos_acesso.eva_data_hora', 'desc');
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
        $searchable['eva_data_hora'] = '=';

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
