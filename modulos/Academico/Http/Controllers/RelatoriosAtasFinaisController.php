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
        '01' => 'janeiro',
        '02' => 'fevereiro',
        '03' => 'março',
        '04' => 'abril',
        '05' => 'maio',
        '06' => 'junho',
        '07' => 'julho',
        '08' => 'agosto',
        '09' => 'setembro',
        '10' => 'outubro',
        '11' => 'novembro',
        '12' => 'dezembro'
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

            $polos = $this->poloRepository->findAllByOfertaCurso($ofc_id)->pluck('pol_nome', 'pol_id');
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

//        return view('Academico::relatoriosatasfinais.relatorioatas', [
//            'curso' => $curso,
//            'polo' => $polo,
//            'turma' => $turma,
//            'oferta' => $ofertaCurso,
//            'matriz' => $matrizTree->toArray(),
//            'resultados' => $resultados,
//            'meses' => $this->meses
//        ]);

        // mpdf
        $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);
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
        $content = view('Academico::relatoriosatasfinais.relatorioatas', [
            'curso' => $curso,
            'polo' => $polo,
            'turma' => $turma,
            'oferta' => $ofertaCurso,
            'matriz' => $matrizTree->toArray(),
            'resultados' => $resultados,
            'meses' => $this->meses
        ])->render();
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit;
    }
}
