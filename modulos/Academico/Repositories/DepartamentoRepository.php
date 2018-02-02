<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Departamento;
use Modulos\Core\Repository\BaseRepository;

class DepartamentoRepository extends BaseRepository
{
    public function __construct(Departamento $departamento)
    {
        $this->model = $departamento;
    }
}
