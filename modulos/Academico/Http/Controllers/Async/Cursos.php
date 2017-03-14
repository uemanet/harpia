<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;

class Cursos
{
    protected $defaultHeaders;
    protected $cursoRepository;

    public function __construct(CursoRepository $cursoRepository)
    {
        $this->cursoRepository = $cursoRepository;
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
}
