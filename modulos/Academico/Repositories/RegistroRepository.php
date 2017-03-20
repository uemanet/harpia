<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Registro;
use Modulos\Core\Repository\BaseRepository;

class RegistroRepository extends BaseRepository
{
    public function __construct(Registro $registro)
    {
        $this->model = $registro;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function matriculaTemRegistro($matriculaId)
    {
        $result = $this->model->where('reg_mat_id', '=', $matriculaId)->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }
}
