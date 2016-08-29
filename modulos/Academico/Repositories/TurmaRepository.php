<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Turma;

class TurmaRepository extends BaseRepository
{
    public function __construct(Turma $turma)
    {
        $this->model = $turma;
    }
}
