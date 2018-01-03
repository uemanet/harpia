<?php


namespace Modulos\Academico\Http\Controllers;

use Harpia\Matriz\MatrizCurricularTree;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Http\Requests\RelatoriosAtasFinaisRequest;
use Modulos\Academico\Repositories\ResultadosFinaisRepository;

class RelatoriosAtasFinaisController extends BaseController
{
    protected $poloRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $ofertaCursoRepository;
    protected $matriculaCursoRepository;
    protected $resultadosFinaisRepository;

    private $meses = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];

    private $dataConclusaoTurma = [
        1 => '2014-12-17',
        2 => '2014-09-14',
        3 => '2014-09-20',
        4 => '2014-11-10',
        5 => '2014-11-10',
        6 => '2014-10-13',
        7 => '2014-11-03',
        8 => '2014-10-26',
        9 => '2015-01-04',
        10 => '2015-01-04',
        11 => '2014-10-13',
        12 => '2015-01-08',
        13 => '2015-01-04',
        14 => '2014-12-17',
        15 => '2014-09-14',
        16 => '2014-11-10',
        17 => '2014-11-03',
        27 => '2016-11-13',
        28 => '2016-10-09',
        29 => '2016-09-25',
        30 => '2016-09-30',
        31 => '2016-09-04',
        32 => '2016-10-23',
        33 => '2016-09-30',
        34 => '2016-12-11',
        35 => '2014-11-03',
        36 => '2016-12-11',
    ];

    public function __construct(
        MatriculaCursoRepository $matriculaCursoRepository,
        CursoRepository $cursoRepository,
        PoloRepository $poloRepository,
        TurmaRepository $turmaRepository,
        OfertaCursoRepository $ofertaCursoRepository,
        ResultadosFinaisRepository $resultadosFinaisRepository
    ) {
        $this->cursoRepository = $cursoRepository;
        $this->poloRepository = $poloRepository;
        $this->turmaRepository = $turmaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->resultadosFinaisRepository = $resultadosFinaisRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->listsCursosTecnicos();

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
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '('.$oferta->mdl_nome.')';
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
            ))->sortable(array('pes_nome', 'mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $situacao = [
            "" => "Selecione a situação",
            "concluido" => "Concluido",
            "reprovado" => "Reprovado",
            "evadido" => "Evadido",
            "trancado" => "Trancado",
            "desistente" => "Desistente"
        ];
        return view('Academico::relatoriosatasfinais.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas', 'polos', 'situacao'));
    }

    public function postPdf(RelatoriosAtasFinaisRequest $request)
    {
        // Recuperar curso e matriz curricular correspondente a oferta
        $curso = $this->cursoRepository->find($request->get('crs_id'));
        $ofertaCurso = $this->ofertaCursoRepository->find($request->get('ofc_id'));
        $turma = $this->turmaRepository->find($request->get('trm_id'));
        $polo = $this->poloRepository->find($request->get('pol_id', null));
        $situacao = $request->get('mat_situacao', "");

        // Estrutura do curso
        $matrizTree = new MatrizCurricularTree($ofertaCurso->matriz);

        // Resultados das matriculas
        $resultados = $this->resultadosFinaisRepository->getResultadosFinais($turma, $polo, $situacao);

        $content = view('Academico::relatoriosatasfinais.relatorioatas', [
            'curso' => $curso,
            'polo' => $polo,
            'turma' => $turma,
            'dataConclusao' => isset($this->dataConclusaoTurma[$turma->trm_id]) ? $this->dataConclusaoTurma[$turma->trm_id] : '',
            'oferta' => $ofertaCurso,
            'matriz' => $matrizTree->toArray(),
            'resultados' => $resultados,
            'meses' => $this->meses
        ])->render();

        // mpdf
        $mpdf = new \mPDF('c', 'A4', '', '', 10, 10, 10, 10, 9, 9);
        $mpdf->debug = true;

        $mpdf->mirrorMargins = 0;
        $mpdf->SetTitle('Relatório de Atas Finais '. $curso->crs_nome);
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit;
    }
}
