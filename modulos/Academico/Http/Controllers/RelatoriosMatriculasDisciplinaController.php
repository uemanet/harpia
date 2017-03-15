<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Validator;

class RelatoriosMatriculasDisciplinaController extends BaseController
{
    protected $matriculaDisciplinaRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    private $ofertaCursoRepository;
    private $periodoLetivoRepository;
    private $ofertaDisciplinaRepository;

    public function __construct(
        MatriculaOfertaDisciplinaRepository $matricula,
        CursoRepository $curso,
        TurmaRepository $turmaRepository,
        OfertaCursoRepository $ofertaCursoRepository,
        PeriodoLetivoRepository $periodoLetivoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository)
    {
        $this->matriculaDisciplinaRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $dados = $request->all();
        $ofertasCurso = [];
        $turmas = [];
        $periodos = [];
        $disciplinas = [];

        if ($dados) {
            $crs_id = $request->input('crs_id');
            $ofc_id = $request->input('ofc_id');
            $trm_id = $request->input('trm_id');
            $per_id = $request->input('per_id');
            $dis = ['ofd_trm_id'=> $trm_id, 'ofd_per_id' => $per_id];

            // dados do async de oferta de curso
            $sqlOfertas = $this->ofertaCursoRepository->findAllByCurso($crs_id);
            $turmas = $this->turmaRepository->findAllByOfertaCurso($ofc_id)->pluck('trm_nome', 'trm_id');
            $periodos = $this->periodoLetivoRepository->getAllByTurma($trm_id)->pluck('per_nome', 'per_id');
            $disciplinas = $this->ofertaDisciplinaRepository->findAll($dis)->pluck('dis_nome', 'ofd_id');
            foreach ($sqlOfertas as $oferta) {
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '('.$oferta->mdl_nome.')';
            }
        }

        $paginacao = null;
        $tabela = null;

        $tableData = $this->matriculaDisciplinaRepository->paginateRequestByParametros($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mat_id' => '#',
                'pes_nome' => 'Nome',
                'situacao_matricula' => 'Situação Matricula'
            ))
              ->sortable(array('pes_nome', 'mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }


        return view('Academico::relatoriosmatriculasdisciplina.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas', 'periodos', 'disciplinas'));
    }

    public function postPdf(Request $request)
    {
        $turmaId = $request->input('trm_id');
        $ofertaId = $request->input('ofd_id');
        $situacao = $request->input('mof_situacao_matricula');
        $dis = ['ofd_id' => $ofertaId];

        $alunos = $this->matriculaDisciplinaRepository->getAllAlunosBySituacao($turmaId, $ofertaId, $situacao);

        $disciplina = $this->ofertaDisciplinaRepository->findAll($dis)->pluck('dis_nome');

        try {
            $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);

            $mpdf->mirrorMargins = 1;
            $mpdf->SetTitle('Relatório de alunos da Disciplina: '. $disciplina[0]);
            $mpdf->SetHeader('{PAGENO} / {nb}');
            $mpdf->SetFooter('São Luís-MA, ' . date("d/m/y"));
            $mpdf->defaultheaderfontsize = 10;
            $mpdf->defaultheaderfontstyle = 'B';
            $mpdf->defaultheaderline = 0;
            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'BI';
            $mpdf->defaultfooterline = 0;
            $mpdf->addPage('L');

            $mpdf->WriteHTML(view('Academico::relatoriosmatriculasdisciplina.relatorioalunos', compact('alunos', 'disciplina'))->render());
            $mpdf->Output();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
