<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Modulos\Academico\Repositories\MatriculaCursoRepository;

class Certificacao
{
    protected $matriculaRepository;

    public function __construct(MatriculaCursoRepository $matriculaCursoRepository)
    {
    }
}
