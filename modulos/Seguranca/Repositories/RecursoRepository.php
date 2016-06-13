<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Recurso;

class RecursoRepository extends BaseRepository
{
    public function __construct(Recurso $recurso)
    {
        $this->model = $recurso;
    }
}
