<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Modulos\Academico\Events\AtualizarSituacaoMatriculaEvent;
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

    /**
     * Altera a situacao da matricula de um aluno.
     *
     * @param Request $request
     *
     * @return static
     *
     * @throws \Exception
     */
    public function postUpdateSituacao(Request $request)
    {
        try {
            $id = $request->input('id');
            $situacao = $request->input('situacao');
            $matricula = $this->matriculaRepository->find($id);

            $update = [
                'mat_situacao' => $situacao,
            ];

            $result = $this->matriculaRepository->update($update, $id, 'mat_id');

            if($matricula->mat_situacao != $update['mat_situacao'] ){
              event(new AtualizarSituacaoMatriculaEvent($matricula));
            }

            return JsonResponse::create($result, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return JsonResponse::create($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
