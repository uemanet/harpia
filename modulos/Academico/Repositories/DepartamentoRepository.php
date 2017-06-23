<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Departamento;

class DepartamentoRepository extends BaseRepository
{
    public function __construct(Departamento $departamento)
    {
        $this->model = $departamento;
    }
}
