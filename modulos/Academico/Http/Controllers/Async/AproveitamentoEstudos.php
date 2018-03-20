<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modulos\Academico\Events\CreateMatriculaDisciplinaEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\AproveitamentoEstudosRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;

class AproveitamentoEstudos extends BaseController
{
    protected $matriculaOfertaDisciplinaRepository;
    protected $matriculaCursoRepository;
    protected $aproveitamentoEstudosRepository;
    protected $ofertaDisciplinaRepository;

    public function __construct(MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                AproveitamentoEstudosRepository $aproveitamentoEstudosRepository,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository)
    {
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->aproveitamentoEstudosRepository = $aproveitamentoEstudosRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;

    }

    public function getTableOfertasDisciplinas($alunoId, $turmaId, $periodoLetivoId = null)
    {

        $disciplinasdisponiveis = $this->aproveitamentoEstudosRepository->getDisciplinesNotEnroledByStudent($alunoId, $turmaId, $periodoLetivoId);

        $html = view('Academico::aproveitamentoestudos.ajax.disciplinasdisponiveis', compact( 'disciplinasdisponiveis'))->render();

        return new JsonResponse($html, 200);
    }

    public function getModal($ofertaId, $matriculaId)
    {

        $data = $this->aproveitamentoEstudosRepository->getCourseConfiguration($ofertaId);

        $turma = $data['turma'];
        $tipo_avaliacao = $data['avaliacao'];
        $conceitos = $data['configuracoes'];

        $html = view('Academico::aproveitamentoestudos.ajax.modal', compact( 'turma', 'tipo_avaliacao', 'conceitos', 'ofertaId', 'matriculaId'))->render();


        return new JsonResponse($html, 200);
    }

}
