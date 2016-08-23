<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\OfertaCurso;

class OfertaCursoRepository extends BaseRepository
{
    public function __construct(OfertaCurso $ofertacurso)
    {
        $this->model = $ofertacurso;
    }
}
