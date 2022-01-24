<?php
declare(strict_types=1);

namespace Modulos\Academico\Repositories;

use DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\PeriodoLetivo;

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
    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        $updated = 0;
        $collection = $this->model->where($attribute, '=', $id)->get();

        if ($collection) {
            foreach ($collection as $obj) {
                $obj->fill($data)->save();
            }

            $updated = $collection->count();
        }

        return $updated;
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

    public function getPeriodosValidos($ofc_ano, $periodo = null)
    {
        $periodosvalidos = $this->model
            ->where('per_fim', '>=', date('Y-m-d'))
            ->orderBy('per_inicio', 'ASC')
            ->pluck('per_nome', 'per_id')
            ->toArray();

        $periodosId = [];

        foreach ($periodosvalidos as $key => $valido) {
            $periodosId[] = $key;
        }

        if ($periodo) {
            $periodosId[] = $periodo;
        }

        return $this->model
            ->whereIn('per_id', $periodosId)
            ->pluck('per_nome', 'per_id')
            ->toArray();
    }

    public function verifyNamePeriodo($periodoName, $periodoId = null): bool
    {
        $result = $this->model->where('per_nome', $periodoName)->get();

        if (!$result->isEmpty()) {
            if (!is_null($periodoId)) {
                $result = $result->where('per_id', $periodoId);

                return $result->isEmpty();
            }

            return !$result->isEmpty();
        }

        return false;
    }

    public function getPeriodoAtual()
    {
        return $this->model
            ->where('per_inicio', '<=', date('Y-m-d'))
            ->where('per_fim', '>=', date('Y-m-d'))
            ->first();
    }
}
