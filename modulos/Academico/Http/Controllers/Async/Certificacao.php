<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\TurmaRepository;

class Certificacao
{
    protected $matriculaCursoRepository;

    public function __construct(MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getAlunosAptos($turma, $modulo)
    {
    }
}
