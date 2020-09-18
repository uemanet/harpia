<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\PeriodoLaboral;

class PeriodoLaboralRepository extends BaseRepository
{
    public function __construct(PeriodoLaboral $periodolaboral)
    {
        $this->model = $periodolaboral;
    }

}
