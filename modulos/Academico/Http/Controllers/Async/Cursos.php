<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\RegistroRepository;

class Cursos
{
    protected $defaultHeaders;
    protected $cursoRepository;
    protected $moduloMatrizRepository;
    protected $matriculaCursoRepository;
    protected $ofertaCursoRepository;
    protected $registroRepository;

    public function __construct(CursoRepository $cursoRepository,
                                ModuloMatrizRepository $moduloMatrizRepository,
                                OfertaCursoRepository $ofertaCursoRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                RegistroRepository $registroRepository)
    {
        $this->cursoRepository = $cursoRepository;
        $this->moduloMatrizRepository = $moduloMatrizRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->registroRepository = $registroRepository;
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

            $modulos->pop();
            $modulos->shift();

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

    public function postCertificarAlunos(Request $request)
    {
        try {
            $requestData = $request->all();
            $registros = [];

            foreach ($requestData['matriculas'] as $key => $value) {
                $data['reg_liv_id'] = 1; // Certificacao;
                $data['reg_mat_id'] = $value;
                $data['reg_mdo_id'] = $requestData['modulo'];

                $registro = $this->registroRepository->create($data);
                $registros[] = $registro->reg_id;
            }
            return new JsonResponse($registros, JsonResponse::HTTP_OK, $this->defaultHeaders);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse($e, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
