<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use DB;
use Modulos\Geral\Repositories\AnexoRepository;

class MatrizesCurriculares extends BaseController
{
    protected $matrizCurricularRepository;
    protected $anexoRepository;

    public function __construct(MatrizCurricularRepository $matrizCurricularRepository, AnexoRepository $anexoRepository)
    {
        $this->matrizCurricularRepository = $matrizCurricularRepository;
        $this->anexoRepository = $anexoRepository;
    }

    public function getFindallbycurso($cursoId)
    {
        $matrizes = $this->matrizCurricularRepository->findAllByCurso($cursoId);

        return new JsonResponse($matrizes, 200);
    }

    public function getFindByOfertaCurso($ofc_id)
    {
        $matriz = $this->matrizCurricularRepository->findByOfertaCurso($ofc_id);

        return new JsonResponse($matriz, 200);
    }

    public function postRemoveAnexo(Request $request)
    {
        try {
            DB::beginTransaction();

            $matrizCurricular = $this->matrizCurricularRepository->find($request->get('mat_id'));
            $dados['mtc_anx_projeto_pedagogico'] = null;
            if ($matrizCurricular->mtc_anx_projeto_pedagogico) {
                if (!$this->matrizCurricularRepository->update($dados, $matrizCurricular->mtc_id, 'mtc_id')) {
                    DB::rollBack();
                    return new JsonResponse("Erro ao atualizar Matriz Curricular", 400);
                }

                $this->anexoRepository->deletarAnexo($matrizCurricular->mtc_anx_projeto_pedagogico);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse("Erro ao remover Anexo", 400);
        }
    }
}
