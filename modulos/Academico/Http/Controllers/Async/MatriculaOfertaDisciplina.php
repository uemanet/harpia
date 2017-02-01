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

    public function getFindAllDisciplinasCursadasByAlunoTurmaPeriodo($alunoId, $turmaId, $periodoId)
    {
        $disciplinas = $this->matriculaOfertaDisciplinaRepository->getDisciplinasCursadasByAluno($alunoId, [
            'ofd_per_id' => $periodoId,
            'ofd_trm_id' => $turmaId
        ]);

        return new JsonResponse($disciplinas, 200);
    }
    
    public function getFindAllDisciplinasNotCursadasByAlunoTurmaPeriodo($alunoId, $turmaId, $periodoId)
    {
        $disciplinas = $this->matriculaOfertaDisciplinaRepository->getDisciplinasOfertadasNotCursadasByAluno($alunoId, $turmaId, $periodoId);

        return new JsonResponse($disciplinas, 200);
    }

    public function getFindAllAlunosMatriculasLote($turmaId, $ofertaId)
    {
        $alunos = $this->matriculaOfertaDisciplinaRepository->getAlunosMatriculasLote($turmaId, $ofertaId);

        return new JsonResponse($alunos, 200);
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

            if($turma->trm_integrada) {
                if(!empty($matriculasCollection)) {
                    foreach($matriculasCollection as $obj) {
                        event(new NovaMatriculaDisciplinaEvent($obj));
                    }
                }
            }

            return new JsonResponse("Alunos matriculados com sucesso!", 200);
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

            if($turma->trm_integrada) {
                if(!empty($matriculas)) {
                    foreach($matriculas as $obj) {
                        event(new NovaMatriculaDisciplinaEvent($obj));
                    }
                }
            }

            return new JsonResponse("Aluno matriculado com sucesso!", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                throw $e;
            }
            return new JsonResponse('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
