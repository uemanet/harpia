<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\NivelCurso;

class NivelCursoRepository extends BaseRepository
{
    public function __construct(NivelCurso $nivelcurso)
    {
        $this->model = $nivelcurso;
    }
}
