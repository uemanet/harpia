<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modulos\Academico\Events\NovaMatriculaDisciplinaEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;

class MatriculaOfertaDisciplina extends BaseController
{
    protected $matriculaOfertaDisciplinaRepository;
    protected $matriculaCursoRepository;

    public function __construct(MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository, MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getTableOfertasDisciplinas($alunoId, $turmaId, $periodoLetivoId)
    {
        $matriculadas = $this->matriculaOfertaDisciplinaRepository->getDisciplinasCursadasByAluno($alunoId, [
            'ofd_per_id' => $periodoLetivoId,
            'ofd_trm_id' => $turmaId,
        ]);

        $naomatriculadas = $this->matriculaOfertaDisciplinaRepository->getDisciplinasOfertadasNotCursadasByAluno($alunoId, $turmaId, $periodoLetivoId);

        $html = view('Academico::matriculadisciplina.ajax.ofertas_disciplinas', compact('matriculadas', 'naomatriculadas'))->render();

        return new JsonResponse($html, 200);
    }

    public function getFindAllAlunosMatriculasLote(Request $request)
    {
        $matriculas = $this->matriculaOfertaDisciplinaRepository->getAlunosMatriculasLote($request->all());

        $html = view('Academico::matriculaslote.ajax.alunos', compact('matriculas'))->render();

        return new JsonResponse($html, 200);
    }

    public function postMatriculasLote(Request $request)
    {
        $matriculas = $request->input('matriculas');
        $ofertaId = $request->input('ofd_id');

        DB::beginTransaction();

        try {
            $matriculasCollection = [];

            foreach ($matriculas as $matricula) {
                $result = $this->matriculaOfertaDisciplinaRepository->createMatricula(['mat_id' => $matricula, 'ofd_id' => $ofertaId]);

                if ($result['type'] == 'error') {
                    DB::rollback();

                    return new JsonResponse($result['message'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $matriculasCollection[] = $result['obj'];
            }

            DB::commit();

            // verifica se a turma dos alunos é integrada
            $matriculaCurso = $this->matriculaCursoRepository->find($matriculas[0]);
            $turma = $matriculaCurso->turma;

            if ($turma->trm_integrada) {
                if (!empty($matriculasCollection)) {
                    foreach ($matriculasCollection as $obj) {
                        event(new NovaMatriculaDisciplinaEvent($obj, "CREATE"));
                    }
                }
            }

            return new JsonResponse('Alunos matriculados com sucesso!', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse('Erro ao tentar matricular. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function postMatricularAlunoDisciplinas(Request $request)
    {
        $ofertas = $request->input('ofertas');
        $matriculaId = $request->input('mof_mat_id');

        DB::beginTransaction();

        try {
            $matriculas = [];

            foreach ($ofertas as $ofertaId) {
                $result = $this->matriculaOfertaDisciplinaRepository->createMatricula(['mat_id' => $matriculaId, 'ofd_id' => $ofertaId]);

                if ($result['type'] == 'error') {
                    DB::rollback();

                    return new JsonResponse($result['message'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $matriculas[] = $result['obj'];
            }

            DB::commit();

            // verifica se a turma do aluno é integrada
            $matriculaCurso = $this->matriculaCursoRepository->find($matriculaId);
            $turma = $matriculaCurso->turma;

            if ($turma->trm_integrada) {
                if (!empty($matriculas)) {
                    foreach ($matriculas as $obj) {
                        event(new NovaMatriculaDisciplinaEvent($obj, "CREATE"));
                    }
                }
            }

            return new JsonResponse('Aluno matriculado com sucesso!', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getRelatorio($turmaId, $ofertaId, $situacao = null)
    {
        $alunos = $this->matriculaOfertaDisciplinaRepository->getAllAlunosBySituacao($turmaId, $ofertaId, $situacao);

        return new JsonResponse($alunos, 200);
    }

    public function postGerarRelatorio(Request $request)
    {
        $turmaId = $request->input('trm_id');
        $ofertaId = $request->input('ofd_id');
        $situacao = $request->input('mof_situacao_matricula');

        $alunos = $this->matriculaOfertaDisciplinaRepository->getAllAlunosBySituacao($turmaId, $ofertaId, $situacao);

        try {
            $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);

            $mpdf->mirrorMargins = 1;
            $mpdf->SetTitle('Relatório de alunos do Curso ');
            $mpdf->SetHeader('{PAGENO} / {nb}');
            $mpdf->SetFooter('São Luís-MA, ' . date("d/m/y"));
            $mpdf->defaultheaderfontsize = 10;
            $mpdf->defaultheaderfontstyle = 'B';
            $mpdf->defaultheaderline = 0;
            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'BI';
            $mpdf->defaultfooterline = 0;
            $mpdf->addPage('L');

            $mpdf->WriteHTML(view('Academico::relatoriosmatriculasdisciplina.relatorioalunos', compact('alunos'))->render());
            $mpdf->Output('Report.pdf', 'D');
        } catch (\Exception $e) {
            throw $e;
        }

//        return new JsonResponse(200);
    }
}
