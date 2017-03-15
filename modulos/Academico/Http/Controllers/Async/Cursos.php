<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;

class Cursos
{
    protected $defaultHeaders;
    protected $cursoRepository;
    protected $moduloMatrizRepository;

    public function __construct(CursoRepository $cursoRepository,
                                ModuloMatrizRepository $moduloMatrizRepository)
    {
        $this->cursoRepository = $cursoRepository;
        $this->moduloMatrizRepository = $moduloMatrizRepository;
        $this->defaultHeaders = ['Content-Type: application/json'];
    }

    public function getCursosTecnicos(Request $request)
    {
        try {
            $cursos = $this->cursoRepository->listsCursosTecnicos();
            return new JsonResponse($cursos, JsonResponse::HTTP_OK, $this->defaultHeaders);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getModulosByCurso($matrizId)
    {
        try {
            $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($matrizId);
            return new JsonResponse($modulos, JsonResponse::HTTP_OK, $this->defaultHeaders);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
