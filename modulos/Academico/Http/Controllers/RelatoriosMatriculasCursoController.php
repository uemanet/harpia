<?php

namespace Modulos\Academico\Http\Controllers;

use App\Exports\MatriculaReportExport;
use Excel;
use Validator;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;

class RelatoriosMatriculasCursoController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $poloRepository;
    private $ofertaCursoRepository;

    public function __construct(
        MatriculaCursoRepository $matricula,
        CursoRepository $curso,
        TurmaRepository $turmaRepository,
        OfertaCursoRepository $ofertaCursoRepository,
        PoloRepository $poloRepository
    ) {
        $this->matriculaCursoRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->poloRepository = $poloRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $dados = $request->all();
        $ofertasCurso = [];
        $turmas = [];
        $polos = [];

        if ($dados) {
            $crs_id = $request->input('crs_id');
            $ofc_id = $request->input('ofc_id');

            $sqlOfertas = $this->ofertaCursoRepository->findAllByCurso($crs_id);
            $turmas = $this->turmaRepository->findAllByOfertaCurso($ofc_id)->pluck('trm_nome', 'trm_id');
            foreach ($sqlOfertas as $oferta) {
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '(' . $oferta->mdl_nome . ')';
            }

            $oferta = $this->ofertaCursoRepository->find($ofc_id);
            $polos = $oferta->polos->pluck('pol_nome', 'pol_id');
        }

        $paginacao = null;
        $tabela = null;

        $tableData = $this->matriculaCursoRepository->paginateRequestByOfertaCurso($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mat_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'pol_nome' => 'Polo',
                'situacao_matricula_curso' => 'Situação Matricula'
            ))
                ->sortable(array('pes_nome', 'mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $situacao = [
            "" => "Selecione a situação",
            "cursando" => "Cursando",
            "concluido" => "Concluido",
            "reprovado" => "Reprovado",
            "evadido" => "Evadido",
            "trancado" => "Trancado",
            "desistente" => "Desistente"
        ];
        return view('Academico::relatoriosmatriculascurso.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas', 'polos', 'situacao'));
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
        $poloId = $request->input('pol_id');

        $matriculas = $this->matriculaCursoRepository->findAllBySitucao(
            ['trm_id' => $turmaId, 'mat_situacao' => $situacao, 'pol_id' => $poloId]);
        $nomecurso = $this->turmaRepository->findCursoByTurma($turmaId);
        $turma = $this->turmaRepository->find($turmaId);

        $date = new Carbon();

        $configs = ['c', 'A4', '', '', 15, 15, 16, 16, 9, 9];
        define('_MPDF_TTFONTDATAPATH', sys_get_temp_dir()."/");
        $mpdf = new mPDF($configs);

        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Relatório de alunos do Curso ' . $nomecurso->crs_nome);
        $mpdf->SetHeader('{PAGENO} / {nb}');
        $mpdf->SetFooter('Emitido em : ' . $date->format('d/m/Y H:i:s'));
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');


        $mpdf->WriteHTML(view('Academico::relatoriosmatriculascurso.relatorioalunos', compact('matriculas', 'nomecurso', 'date', 'turma'))->render());
        $mpdf->Output();
        exit;
    }

    public function postXls(Request $request)
    {
        $rules = [
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'trm_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $turmaId = $request->input('trm_id');
        $situacao = $request->input('mat_situacao');
        $poloId = $request->input('pol_id');

        $matriculas = $this->matriculaCursoRepository->findAllBySitucao([
            'trm_id' => $turmaId,
            'mat_situacao' => $situacao,
            'pol_id' => $poloId
        ]);

        $curso = $this->turmaRepository->findCursoByTurma($turmaId);

        $turma = $this->turmaRepository->find($turmaId);

        return Excel::download(new MatriculaReportExport(array('trm_id' => $turmaId, 'mat_situacao' => $situacao, 'pol_id' => $poloId), $matriculas, $curso, $turma), 'Relatório de alunos do curso: ' . str_replace("/", "-", $curso->crs_nome).'.xlsx');

    }
}
