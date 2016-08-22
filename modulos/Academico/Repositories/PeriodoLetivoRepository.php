<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\PeriodoLetivo;

class PeriodoLetivoRepository extends BaseRepository
{
    public function __construct(PeriodoLetivo $periodoLetivo)
    {
        $this->model = $periodoLetivo;
    }
}
