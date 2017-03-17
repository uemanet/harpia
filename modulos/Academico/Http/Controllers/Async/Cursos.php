<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;

class Cursos
{
    protected $defaultHeaders;
    protected $cursoRepository;
    protected $moduloMatrizRepository;
    protected $matriculaCursoRepository;
    protected $ofertaCursoRepository;

    public function __construct(CursoRepository $cursoRepository,
                                ModuloMatrizRepository $moduloMatrizRepository,
                                OfertaCursoRepository $ofertaCursoRepository,
                                MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->cursoRepository = $cursoRepository;
        $this->moduloMatrizRepository = $moduloMatrizRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
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

    public function getModulosByOferta($ofertaId)
    {
        try {
            $oferta = $this->ofertaCursoRepository->find($ofertaId);
            $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($oferta->ofc_mtc_id);
            return new JsonResponse($modulos, JsonResponse::HTTP_OK, $this->defaultHeaders);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAlunosAptos($turma, $modulo)
    {
        try {
            $alunos = $this->matriculaCursoRepository->getAlunosAptosCertificacao($turma, $modulo);
            return new JsonResponse($alunos, JsonResponse::HTTP_OK, $this->defaultHeaders);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse($e, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
