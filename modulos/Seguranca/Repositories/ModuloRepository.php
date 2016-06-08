<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Modulo;

class ModuloRepository extends BaseRepository
{
    public function __construct(Modulo $modulo)
    {
        $this->model = $modulo;
    }
}