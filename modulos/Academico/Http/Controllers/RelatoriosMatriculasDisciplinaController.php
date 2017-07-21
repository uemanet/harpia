<?php

namespace Modulos\Academico\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\PoloRepository;
use Validator;

class RelatoriosMatriculasDisciplinaController extends BaseController
{
    protected $matriculaDisciplinaRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $poloRepository;
    private $ofertaCursoRepository;
    private $periodoLetivoRepository;
    private $ofertaDisciplinaRepository;

    public function __construct(
        MatriculaOfertaDisciplinaRepository $matricula,
        CursoRepository $curso,
        TurmaRepository $turmaRepository,
        OfertaCursoRepository $ofertaCursoRepository,
        PeriodoLetivoRepository $periodoLetivoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        PoloRepository $poloRepository)
    {
        $this->matriculaDisciplinaRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->poloRepository = $poloRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $dados = $request->all();
        $ofertasCurso = [];
        $turmas = [];
        $polos = [];
        $periodos = [];
        $disciplinas = [];

        if ($dados) {
            $crs_id = $request->input('crs_id');
            $ofc_id = $request->input('ofc_id');
            $trm_id = $request->input('trm_id');
            $per_id = $request->input('per_id');
            $dis = ['ofd_trm_id' => $trm_id, 'ofd_per_id' => $per_id];

            // dados do async de oferta de curso
            $sqlOfertas = $this->ofertaCursoRepository->findAllByCurso($crs_id);
            $turmas = $this->turmaRepository->findAllByOfertaCurso($ofc_id)->pluck('trm_nome', 'trm_id');
            $periodos = $this->periodoLetivoRepository->getAllByTurma($trm_id)->pluck('per_nome', 'per_id');
            $disciplinas = $this->ofertaDisciplinaRepository->findAll($dis)->pluck('dis_nome', 'ofd_id');
            foreach ($sqlOfertas as $oferta) {
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '(' . $oferta->mdl_nome . ')';
            }
            $polos = $this->poloRepository->findAllByOfertaCurso($ofc_id)->pluck('pol_nome', 'pol_id');
        }

        $paginacao = null;
        $tabela = null;

        $tableData = $this->matriculaDisciplinaRepository->paginateRequestByParametros($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mat_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'pol_nome' => 'Polo',
                'situacao_matricula' => 'Situação Matricula'
            ))
                ->sortable(array('pes_nome', 'mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $situacao = [
                      "" => "Selecione o status",
                      "cursando" => "Cursando",
                      "aprovado_media" => "Aprovado por Média",
                      "aprovado_final" => "Aprovado por Final",
                      "reprovado_media" => "Reprovado por Média",
                      "reprovado_final" => "Reprovado por Final",
                      "cancelado" => "Cancelado"
                   ];

        return view('Academico::relatoriosmatriculasdisciplina.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas', 'periodos', 'disciplinas', 'polos', 'situacao'));
    }

    public function postPdf(Request $request)
    {
        $rules = [
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'per_id' => 'required',
            'ofd_id' => 'required',
            'trm_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $turmaId = $request->input('trm_id');
        $ofertaId = $request->input('ofd_id');
        $situacao = $request->input('mof_situacao_matricula');
        $poloId = $request->input('pol_id');
        $dis = ['ofd_id' => $ofertaId];

        $alunos = $this->matriculaDisciplinaRepository->getAllAlunosBySituacao($turmaId, $ofertaId, $situacao, $poloId);

        $disciplina = $this->ofertaDisciplinaRepository->findAll($dis)->pluck('dis_nome');
        $turma = $this->turmaRepository->find($turmaId);

        $date = new Carbon();

        $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);

        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Relatório de alunos da Disciplina: ' . $disciplina[0]);
        $mpdf->SetHeader('{PAGENO} / {nb}');
        $mpdf->SetFooter('Emitido em : ' . $date->format('d/m/Y H:i:s'));
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');

        $mpdf->WriteHTML(view('Academico::relatoriosmatriculasdisciplina.relatorioalunos', compact('alunos', 'disciplina', 'date', 'turma'))->render());
        $mpdf->Output();
        exit;
    }

    public function postXls(Request $request)
    {
        $rules = [
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'per_id' => 'required',
            'ofd_id' => 'required',
            'trm_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $turmaId = $request->input('trm_id');
        $ofertaId = $request->input('ofd_id');
        $situacao = $request->input('mof_situacao_matricula');
        $poloId = $request->input('pol_id');
        $dis = ['ofd_id' => $ofertaId];

        $alunos = $this->matriculaDisciplinaRepository->getAllAlunosBySituacao($turmaId, $ofertaId, $situacao, $poloId);

        $disciplina = $this->ofertaDisciplinaRepository->findAll($dis)->pluck('dis_nome');
        $turma = $this->turmaRepository->find($turmaId);

        $date = new Carbon();

        $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);

        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Relatório de alunos da Disciplina: ' . $disciplina[0]);
        $mpdf->SetHeader('{PAGENO} / {nb}');
        $mpdf->SetFooter('Emitido em : ' . $date->format('d/m/Y H:i:s'));
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');

        $html = view('Academico::relatoriosmatriculasdisciplina.relatorioalunos', compact('alunos', 'disciplina', 'date', 'turma'))->render();

        $arquivo = 'Matriculados na disciplina '.$disciplina.'.xls';
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$arquivo");
        header("Pragma: no-cache");
        header("Expires: 0");
        print chr(255) . chr(254) . mb_convert_encoding($html, 'UTF-16LE', 'UTF-8');
        exit;
    }
}
