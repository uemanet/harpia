<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

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

        $disciplina = $this->disciplinaRepository->find($dados['dis_id']);

        $disciplinaExists = $this->matrizCurricularRepository->verifyIfDisciplinaExistsInMatriz($dados['mtc_id'], $dados['dis_id']);

        if ($disciplinaExists) {
            return new JsonResponse('Disciplina duplicada', Response::HTTP_BAD_REQUEST);
        }

        $disciplinaNameExists = $this->matrizCurricularRepository->verifyIfNomeDisciplinaExistsInMatriz($dados['mtc_id'], $disciplina->dis_nome);

        if ($disciplinaNameExists) {
            return new JsonResponse('Já existe uma disciplina com esse nome', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }

        if($dados['tipo_disciplina'] == 'tcc')
        {
            $disciplinaTccExists = $this->matrizCurricularRepository->verifyIfExistsDisciplinaTccInMatriz($dados['mtc_id']);

            if($disciplinaTccExists) {
                return new JsonResponse('Já existe uma disciplina do tipo TCC cadastrada nessa matriz', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
            }
        }

        try {
            $modulodisciplina['mdc_dis_id'] = $dados['dis_id'];
            $modulodisciplina['mdc_mdo_id'] = $dados['mod_id'];
            $modulodisciplina['mdc_tipo_avaliacao'] = $dados['tipo_avaliacao'];
            $modulodisciplina['mdc_tipo_disciplina'] = $dados['tipo_disciplina'];

            $disciplinaCreate = $this->moduloDisciplinaRepository->create($modulodisciplina);

            return new JsonResponse(['mdc_id' => $disciplinaCreate->mdc_id], Response::HTTP_OK);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return new JsonResponse('CODE: ' . $e->getCode() . ' - Message: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new JsonResponse('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function postDeletarDisciplina(Request $request)
    {
        $dados = $request->all();

        $moduloDisciplina = $this->moduloDisciplinaRepository->find($dados['mdc_id']);

        $disciplinaExists = $this->matrizCurricularRepository->verifyIfDisciplinaExistsInMatriz($dados['mtc_id'], $moduloDisciplina->mdc_dis_id);

        if (!$disciplinaExists) {
            return new JsonResponse('Disciplina não cadastrada para esta matriz', Response::HTTP_BAD_REQUEST);
        }

        if ($this->moduloDisciplinaRepository->delete($dados['mdc_id'])) {
            return new JsonResponse(Response::HTTP_OK);
        } else {
            return new JsonResponse(Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAllDisciplinasByModulo($moduloId)
    {
        $disciplina = $this->moduloDisciplinaRepository->getAllDisciplinasByModulo($moduloId);

        return new JsonResponse($disciplina, 200);
    }
}
