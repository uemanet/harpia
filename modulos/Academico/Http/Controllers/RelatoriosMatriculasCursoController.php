<?php

namespace Modulos\Academico\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Validator;

class RelatoriosMatriculasCursoController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    private $ofertaCursoRepository;

    public function __construct(MatriculaCursoRepository $matricula,
                                CursoRepository $curso,
                                TurmaRepository $turmaRepository,
                                OfertaCursoRepository $ofertaCursoRepository)
    {
        $this->matriculaCursoRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $dados = $request->all();
        $ofertasCurso = [];
        $turmas = [];

        if ($dados) {
            $crs_id = $request->input('crs_id');
            $ofc_id = $request->input('ofc_id');

            $sqlOfertas = $this->ofertaCursoRepository->findAllByCurso($crs_id);
            $turmas = $this->turmaRepository->findAllByOfertaCurso($ofc_id)->pluck('trm_nome', 'trm_id');
            foreach ($sqlOfertas as $oferta) {
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '('.$oferta->mdl_nome.')';
            }
        }

        $paginacao = null;
        $tabela = null;

        $tableData = $this->matriculaCursoRepository->paginateRequestByOfertaCurso($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mat_id' => '#',
                'pes_nome' => 'Nome',
                'mat_situacao' => 'Situação Matricula'
            ))
                ->sortable(array('pes_nome', 'mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::relatoriosmatriculascurso.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas'));
    }

    public function postPdf(Request $request)
    {
        $rules = [
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'trm_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $turmaId = $request->input('trm_id');
        $situacao = $request->input('mat_situacao');
        $matriculas = $this->matriculaCursoRepository->findAllBySitucao(
            ['trm_id' => $turmaId, 'mat_situacao' => $situacao]);
        $nomecurso = $this->turmaRepository->findCursoByTurma($turmaId);

        $date = new Carbon();

        $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);

        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Relatório de alunos do Curso ' . $nomecurso->crs_nome);
        $mpdf->SetHeader('{PAGENO} / {nb}');
        $mpdf->SetFooter('Emitdo em : '. $date->format('d/m/Y H:i:s'));
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');


        $mpdf->WriteHTML(view('Academico::relatoriosmatriculascurso.relatorioalunos', compact('matriculas', 'nomecurso', 'date'))->render());
        $mpdf->Output();
        exit;
    }
}
