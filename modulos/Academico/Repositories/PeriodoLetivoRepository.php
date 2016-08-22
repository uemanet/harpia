<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\PeriodoLetivo;
use Carbon\Carbon;

class PeriodoLetivoRepository extends BaseRepository
{
    public function __construct(PeriodoLetivo $periodoLetivo)
    {
        $this->model = $periodoLetivo;
    }

    /**
     * Formata datas do padrao pt-BR para o
     * padrao default antes de salvar o registro
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $data['per_inicio'] = Carbon::createFromFormat('d/m/Y', $data['per_inicio'])->toDateString();
        $data['per_fim'] = Carbon::createFromFormat('d/m/Y', $data['per_fim'])->toDateString();

        return $this->model->create($data);
    }


    public function update(array $data, $id, $attribute = "id")
    {
        $data['per_inicio'] = Carbon::createFromFormat('d/m/Y', $data['per_inicio'])->toDateString();
        $data['per_fim'] = Carbon::createFromFormat('d/m/Y', $data['per_fim'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }
}
