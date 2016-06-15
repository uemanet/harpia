<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Colaborador;

class ColaboradorRepository extends BaseRepository
{
    public function __construct(Colaborador $colaborador)
    {
        $this->model = $colaborador;
    }
}