<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modulos\Academico\Events\CreateMatriculaDisciplinaEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;

class AproveitamentoEstudos extends BaseController
{
    protected $matriculaOfertaDisciplinaRepository;
    protected $matriculaCursoRepository;

    public function __construct(MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
                                MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getTableOfertasDisciplinas($alunoId, $turmaId, $periodoLetivoId)
    {

        $disciplinasdisponiveis = $this->matriculaOfertaDisciplinaRepository->getDisciplinesNotEnroledByStudent($alunoId, $turmaId, $periodoLetivoId);

        $html = view('Academico::aproveitamentoestudos.ajax.disciplinasdisponiveis', compact( 'disciplinasdisponiveis'))->render();

        return new JsonResponse($html, 200);
    }

}
