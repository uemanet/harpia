<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\HoraTrabalhada;

class HoraTrabalhadaRepository extends BaseRepository
{
    public function __construct(HoraTrabalhada $horaTrabalhada)
    {
        $this->model = $horaTrabalhada;
    }

}
