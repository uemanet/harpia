<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Matriculas\Models\Seletivo;

class SeletivoRepository extends BaseRepository
{
    public function __construct(Seletivo $seletivo)
    {
        $this->model = $seletivo;
    }
}
