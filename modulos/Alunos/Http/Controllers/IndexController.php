<?php

namespace Modulos\Alunos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\HistoricoParcialRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Alunos\Repositories\ComprovanteMatriculaRepository;
use Mpdf\Mpdf;
use Ramsey\Uuid\Uuid;

/**
 * Class IndexController.
 */
class IndexController extends Controller
{

    private $alunoRepository;
    private $matriculaCursoRepository;
    private $historicoParcialRepository;
    private $periodoLetivoRepository;
    private $matriculaOfertaDisciplinaRepository;
    private $comprovanteMatriculaRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        HistoricoParcialRepository $historicoParcialRepository,
        PeriodoLetivoRepository $periodoLetivoRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        ComprovanteMatriculaRepository $comprovanteMatriculaRepository
    )
    {
        $this->alunoRepository = $alunoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->historicoParcialRepository = $historicoParcialRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->comprovanteMatriculaRepository = $comprovanteMatriculaRepository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $aluno = Auth::user()->aluno;

        $situacao = [
            'cursando' => 'Cursando',
            'reprovado' => 'Reprovado',
            'evadido' => 'Evadido',
            'trancado' => 'Trancado',
            'desistente' => 'Desistente'
        ];

        $matriculas = $aluno->matriculas;

        foreach ($matriculas as $matricula){
            $matricula->progress = (int)($this->matriculaCursoRepository->getStudentProgress($matricula)*100);
            $matricula->coefficient = $this->matriculaCursoRepository->getStudentCoefficient($matricula);
        }

        return view('Alunos::index.index', compact('aluno', 'situacao', 'matriculas'));
    }

    public function getComprovanteMatricula($matriculaId)
    {
        $matricula = $this->matriculaCursoRepository->find($matriculaId);

        if (!$matricula) {
            flash()->error('Aluno não encontrado');
            return redirect()->back();
        }

        if ($matricula->mat_situacao !== 'cursando') {
            flash()->error('Apenas alunos com situação cursando podem emitir o comprovante de matrícula');
            return redirect()->back();
        }

        $comprovanteMatricula = $this
            ->comprovanteMatriculaRepository
            ->getComprovanteMatricula($matriculaId);

        $aluno = $matricula->aluno;
        $nome = explode(' ', $aluno->pessoa->pes_nome);
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        if($comprovanteMatricula){
            $data = json_decode($comprovanteMatricula->aln_dados_matricula);

            $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir() . '/']);
            $mpdf->mirrorMargins = 1;
            $mpdf->SetTitle('Comprovante de Matrícula - ' . $aluno->pessoa->pes_nome);
            $mpdf->addPage('P');

            $mpdf->WriteHTML(view('Alunos::comprovante.matricula', compact('aluno', 'data'))->render());
            $mpdf->Output('Historico_Parcial_' . $nome[0] . '_' . end($nome) . '.pdf', 'I');
            return;
        }

        $periodo = $this->periodoLetivoRepository->getPeriodoAtual();
        $disciplinasCursadas = $this->matriculaOfertaDisciplinaRepository->findBy([
            ['mof_mat_id', '=', $matricula->mat_id],
            ['mof_situacao_matricula', '<>', 'cancelado'],
            ['ofd_per_id', '=', $periodo->per_id]
        ], null, ['dis_nome' => 'asc', 'mdo_id' => 'asc']);

        $curso = $matricula->turma->ofertacurso->curso;

        $aluno->cpf = '';

        $cpf = $aluno->pessoa->documentos()->where('doc_tpd_id', 2)->first();

        if ($cpf) {
            $aluno->cpf = $cpf->doc_conteudo;
        }

        $aluno->rg = [];
        $rg = $aluno->pessoa->documentos()->where('doc_tpd_id', 1)->first();

        if ($rg) {
            $aluno->rg = [
                'conteudo' => $rg->doc_conteudo,
                'orgao' => $rg->doc_orgao,
                'data_expedicao' => $rg->doc_data_expedicao
            ];
        }

        $matricula->polo = $matricula->polo;

        $data = 'São Luís, ' . strftime('%d de %B de %Y', strtotime('today'));

        // Adicionar ao livro
        $uuid = Uuid::uuid4();

        $data = [
            'disciplinas' => $disciplinasCursadas,
            'aluno' => $aluno,
            'curso' => $curso,
            'matricula' => $matricula,
            'data' => $data,
            'uuid' => $uuid->toString()
        ];

        $data = json_encode($data);

        $this->comprovanteMatriculaRepository->create(
            [
                'aln_dados_matricula' => $data,
                'aln_mat_id' => $matriculaId,
                'aln_codigo' =>
                    $uuid->toString()
            ]
        );

        $data = json_decode($data);

        $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir() . '/']);
        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Comprovante de Matrícula - ' . $aluno->pessoa->pes_nome);
        $mpdf->addPage('P');

        $mpdf->WriteHTML(view('Alunos::comprovante.matricula', compact('aluno', 'data'))->render());
        $mpdf->Output('Historico_Parcial_' . $nome[0] . '_' . end($nome) . '.pdf', 'I');
    }

    public function getVerificaComprovanteMatricula()
    {
        return view('Alunos::comprovante.verifica');
    }

    public function postVerificaComprovanteMatricula(Request $request)
    {

        $data = $request->all();

        $comprovante = $this
            ->comprovanteMatriculaRepository
            ->getComprovanteMatriculaByCodigo($data['aln_codigo']);

        if (!$comprovante) {
            flash()->error('Código não existe');
            return redirect()->back();
        }

        $data = json_decode($comprovante->aln_dados_matricula);

        $matricula = $this->matriculaCursoRepository->find($comprovante->aln_mat_id);
        $aluno = $matricula->aluno;

        return view('Alunos::comprovante.verificado', ['data' => $data, 'aluno' => $aluno]);
    }
}
