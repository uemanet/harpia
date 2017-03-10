<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Validator;

class RelatoriosMatriculasDisciplinaController extends BaseController
{
    protected $matriculaDisciplinaRepository;
    protected $cursoRepository;
    protected $turmaRepository;

    public function __construct(MatriculaOfertaDisciplinaRepository $matricula, CursoRepository $curso, TurmaRepository $turmaRepository)
    {
        $this->matriculaDisciplinaRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::relatoriosmatriculasdisciplina.index', compact('cursos'));
    }

    public function postPdf(Request $request)
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
        $matriculas = $this->matriculaCursoRepository->findAll(
            ['mat_trm_id' => $turmaId], null, ['pes_nome' => 'asc']);
        $nomecurso = $this->turmaRepository->findCursoByTurma($turmaId);


        $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);

        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Relatório de alunos do Curso ' . $nomecurso->crs_nome);
        $mpdf->SetHeader('{PAGENO} / {nb}');
        $mpdf->SetFooter('São Luís-MA, ' . date("d/m/y"));
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');


        $mpdf->WriteHTML(view('Academico::relatoriosmatriculas.relatorioalunos', compact('matriculas', 'nomecurso'))->render());
        $mpdf->Output();
        exit;
    }
}
