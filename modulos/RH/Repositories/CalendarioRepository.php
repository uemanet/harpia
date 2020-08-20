<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Calendario;

class CalendarioRepository extends BaseRepository
{
    public function __construct(Calendario $calendario)
    {
        $this->model = $calendario;
    }

}
