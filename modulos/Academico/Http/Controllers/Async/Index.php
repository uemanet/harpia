<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class Index extends BaseController
{
    private $matriculaRepository;
    private $cursoRepository;

    public function __construct(MatriculaCursoRepository $matriculaCursoRepository, CursoRepository $cursoRepository)
    {
        $this->matriculaRepository = $matriculaCursoRepository;
        $this->cursoRepository = $cursoRepository;
    }

    public function getCursoPorNivelData()
    {
        $result = $this->cursoRepository->getCursosPorNivel();
        return new JsonResponse($result, 200);
    }

    public function getMatriculaPorStatusData()
    {
        $result = $this->matriculaRepository->getMatriculasPorStatus();

        return new JsonResponse($result, 200);
    }

    public function getMatriculasPorMes()
    {
        $result = $this->matriculaRepository->getMatriculasPorMesUltimosSeisMeses();
        return new JsonResponse($result, 200);
    }
}
