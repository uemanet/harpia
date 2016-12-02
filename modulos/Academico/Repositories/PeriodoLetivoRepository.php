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
                        ->where('per_id', '>=', function ($query) use ($turmaId) {
                            $query->select('trm_per_id')
                                    ->from('acd_turmas')
                                    ->where('trm_id', '=', $turmaId);
                        })
                        ->get();
        
        return $result;
    }
}
