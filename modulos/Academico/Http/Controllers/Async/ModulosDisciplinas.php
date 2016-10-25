<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ModulosDisciplinas extends BaseController
{
    protected $disciplinasRepository;
    protected $matrizCurricularRepository;

    public function __construct(ModuloDisciplinaRepository $disciplina, MatrizCurricularRepository $matrizCurricularRepository)
    {
        $this->disciplinasRepository = $disciplina;
        $this->matrizCurricularRepository = $matrizCurricularRepository;
    }

    public function getFindbynome($nome)
    {
        $disciplinas = $this->disciplinasRepository->buscar($nome);

        if($disciplinas)
        {
            return new JsonResponse($disciplinas, Response::HTTP_OK);
        }

        return new JsonResponse('Sem registros', Response::HTTP_NOT_FOUND);
    }

    public function postAdicionarDisciplina(Request $request)
    {
        $dados = $request->except('_token');

        // TODO: 1
        // verificar se a disciplina já está adicionada na matriz

        $disciplinaExists = $this->matrizCurricularRepository->verifyIfDisciplinaExistsInMatriz($dados['mtc_id'], $dados['dis_id']);

        if ($disciplinaExists) {
            return new JsonResponse('Disciplina duplicada', Response::HTTP_BAD_REQUEST);
        }

        try {
            // TODO: 2
            // Salvar

//            $disciplinaCreate = $this->disciplinasRepository->create();

            // TODO: 3
            //Retornar o mdc_id
            return new JsonResponse(['mtc_id' => 10], Response::HTTP_OK);
        } catch (\Exception $e) {

            // TODO: 4
            //Caso dê erro retornar o erro

            if (config('app.debug')) {
                return new JsonResponse('CODE: ' . $e->getCode() . ' - Message: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new JsonResponse('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function postDelete(Request $request)
    {
        $disciplinaExists = $this->matrizCurricularRepository->verifyIfDisciplinaExistsInMatriz($dados['mtc_id'], $dados['dis_id']);

        if (!$disciplinaExists) {
            return new JsonResponse('Disciplina não cadastrada para esta matriz', Response::HTTP_BAD_REQUEST);
        }

        // TODO: 1
        // DELETAR

        // TODO: 2
        // ENVIAR RETORNO
    }
}
