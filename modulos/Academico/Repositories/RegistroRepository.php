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
}
