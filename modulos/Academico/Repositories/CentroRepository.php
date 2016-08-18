<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Centro;

class CentroRepository extends BaseRepository
{
    public function __construct(Centro $centro)
    {
        $this->model = $centro;
    }
}
