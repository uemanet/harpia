<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteTurma;

class AmbienteTurmaRepository extends BaseRepository
{
    public function __construct(AmbienteTurma $ambienteturma)
    {
        $this->model = $ambienteturma;
    }
}
