<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\PeriodoLetivo;
use Carbon\Carbon;
use DB;

class PeriodoLetivoRepository extends BaseRepository
{
    public function __construct(PeriodoLetivo $periodoLetivo)
    {
        $this->model = $periodoLetivo;
    }

    /**
     * Formata datas pt_BR para default MySQL
     * para update de registros
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data['per_inicio'] = Carbon::createFromFormat('d/m/Y', $data['per_inicio'])->toDateString();
        $data['per_fim'] = Carbon::createFromFormat('d/m/Y', $data['per_fim'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    public function getAllByTurma($turmaId)
    {
        $result = $this->model
                        ->where('per_fim', '>=', function ($query) use ($turmaId) {
                            $query->select('per_fim')
                                    ->from('acd_turmas')
                                    ->join('acd_periodos_letivos', 'trm_per_id', '=', 'per_id')
                                    ->where('trm_id', '=', $turmaId);
                        })
                        ->orderBy('per_inicio', 'ASC')
                        ->get();
        
        return $result;
    }

    public function getPeriodosValidos($ofc_ano)
    {
        return $this->model
                    ->whereYear('per_inicio', $ofc_ano)
                    ->where('per_fim', '>=', date('Y-m-d'))
                    ->orderBy('per_inicio', 'ASC')
                    ->pluck('per_nome', 'per_id')
                    ->toArray();
    }

    public function verifyNamePeriodo($periodoName, $periodoId = null)
    {
        $result = $this->model->where('per_nome', $periodoName)->get();

        if (!$result->isEmpty()) {
            if (!is_null($periodoId)) {
                $result = $result->where('per_id', $periodoId);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
