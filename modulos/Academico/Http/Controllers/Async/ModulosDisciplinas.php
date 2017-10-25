<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use DB;

class ModulosDisciplinas extends BaseController
{
    protected $disciplinaRepository;
    protected $moduloDisciplinaRepository;
    protected $matrizCurricularRepository;

    public function __construct(ModuloDisciplinaRepository $moduloDisciplinaRepository, MatrizCurricularRepository $matrizCurricularRepository, DisciplinaRepository $disciplinaRepository)
    {
        $this->moduloDisciplinaRepository = $moduloDisciplinaRepository;
        $this->matrizCurricularRepository = $matrizCurricularRepository;
        $this->disciplinaRepository = $disciplinaRepository;
    }

    public function postAdicionarDisciplina(Request $request)
    {
        $dados = $request->except('_token');

        try {
            $response = $this->moduloDisciplinaRepository->create($dados);

            if ($response['type'] == 'error') {
                return new JsonResponse($response['message'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
            }

            return new JsonResponse(['mdc_id' => $response['data']['mdc_id']], Response::HTTP_OK);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return new JsonResponse('CODE: '.$e->getCode().' - Message: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new JsonResponse('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function postDeletarDisciplina(Request $request)
    {
        $dados = $request->all();

        DB::beginTransaction();

        try {
            $moduloDisciplina = $this->moduloDisciplinaRepository->find($dados['mdc_id']);

            $disciplinaExists = $this->matrizCurricularRepository->verifyIfDisciplinaExistsInMatriz($dados['mtc_id'], $moduloDisciplina->mdc_dis_id);

            if (!$disciplinaExists) {
                return new JsonResponse('Disciplina não cadastrada para esta matriz', Response::HTTP_BAD_REQUEST);
            }

            // Ao deletar a disciplina, caso ela seja pré-requisito de outra, precisa-se removê-la da lista dos outros registros
            if ($this->moduloDisciplinaRepository->delete($dados['mdc_id'])) {
                $this->moduloDisciplinaRepository->updatePreRequisitos($dados['mtc_id'], $dados['mdc_id']);
                DB::commit();

                return new JsonResponse(Response::HTTP_OK);
            }

            DB::rollback();

            return new JsonResponse(Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                return new JsonResponse('CODE: '.$e->getCode().' - Message: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new JsonResponse('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllDisciplinasByModulo($moduloId)
    {
        $disciplina = $this->moduloDisciplinaRepository->getAllDisciplinasByModulo($moduloId);

        return new JsonResponse($disciplina, 200);
    }

    public function getDisciplinasNotOfertadasByModulo($moduloId, $turmaId, $periodoId)
    {
        $disciplina = $this->moduloDisciplinaRepository->getAllDisciplinasNotOfertadasByModulo($moduloId, $turmaId, $periodoId);

        return new JsonResponse($disciplina, 200);
    }

    public function getDisciplina($id)
    {
        $disciplina = [];
        $prerequisitos = [];

        if ($this->moduloDisciplinaRepository->find($id)) {
            $disciplina = $this->moduloDisciplinaRepository->find($id);

            if ($disciplina->mdc_pre_requisitos) {
                $prerequisitos = $this->moduloDisciplinaRepository->getDisciplinasPreRequisitos($id);
                $disciplina = $disciplina->toArray();

                $disciplina['mdc_pre_requisitos'] = $prerequisitos;
            }
        }

        return new JsonResponse($disciplina, 200, [
           'content-type' => 'application/json'
        ]);
    }
}
