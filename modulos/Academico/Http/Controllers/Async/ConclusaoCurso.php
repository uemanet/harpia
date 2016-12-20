<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class ConclusaoCurso extends BaseController
{
    protected $matriculaCursoRepository;

    public function __construct(MatriculaCursoRepository $matricula)
    {
        $this->matriculaCursoRepository = $matricula;
    }

    public function getAllalunosaptosounao(Request $request)
    {
        $dados = $request->all();

        $alunos = $this->matriculaCursoRepository->getAlunosAptosOrNot($dados['ofc_id'], $dados['trm_id'], $dados['pol_id']);

        return new JsonResponse($alunos, 200);
    }
}