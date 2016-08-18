<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Professor;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $professor )
    {
        $this->model = $professor;
    }
}
