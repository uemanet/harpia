<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\PeriodoGozo;

class PeriodoGozoRepository extends BaseRepository
{
    public function __construct(PeriodoGozo $periodo_gozo)
    {
        $this->model = $periodo_gozo;
    }
}
