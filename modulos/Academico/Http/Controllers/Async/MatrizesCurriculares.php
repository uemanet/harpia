<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\MatrizCurricularRepository;

class MatrizesCurriculares extends BaseController
{
    protected $matrizCurricularRepository;

    public function __construct(MatrizCurricularRepository $matrizCurricularRepository)
    {
        $this->matrizCurricularRepository = $matrizCurricularRepository;
    }

    public function getFindallbycurso($cursoId)
    {
        $matrizes = $this->matrizCurricularRepository->findAllByCurso($cursoId);

        return new JsonResponse($matrizes, 200);
    }
}
