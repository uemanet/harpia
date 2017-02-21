<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class Matricula extends BaseController
{
    protected $matriculaRepository;

    public function __construct(MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->matriculaRepository = $matriculaCursoRepository;
    }

    public function postUpdateSituacao(Request $request)
    {
        return JsonResponse::create(null, 200);
        try {
            $id = $request->input('id');
            $situacao = $request->input('situacao');

            $update = [
                'mat_situacao' => $situacao
            ];

            $result = $this->matriculaRepository->update($update, $id, 'mat_id');
            return JsonResponse::create($result, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return JsonResponse::create($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
