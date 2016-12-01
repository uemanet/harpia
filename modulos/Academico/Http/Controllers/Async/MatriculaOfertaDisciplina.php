<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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

    public function getFindAllDisciplinasByAlunoTurmaPeriodo($alunoId, $turmaId, $periodoId)
    {
        $disciplinas = $this->matriculaOfertaDisciplinaRepository->getDisciplinasOfertadasAndCursadasByAluno($alunoId, $turmaId, $periodoId);

        return new JsonResponse($disciplinas, 200);
    }

    public function postMatricularAlunoDisciplinas(Request $request)
    {
        $ofertas = $request->input('ofertas');
        $matriculaId = $request->input('mof_mat_id');

        DB::beginTransaction();

        try {
            foreach($ofertas as $ofertaId) {
                if(!$this->matriculaOfertaDisciplinaRepository->verifyQtdVagas($ofertaId)) {
                    return new JsonResponse("Sem vagas disponiveis", Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $matricula = $this->matriculaOfertaDisciplinaRepository->verifyMatriculaDisciplina($matriculaId, $ofertaId);
                if (!$matricula){
                    $this->matriculaOfertaDisciplinaRepository->create([
                        'mof_mat_id' => $matriculaId,
                        'mof_ofd_id' => $ofertaId,
                        'mof_tipo_matricula' => 'matriculacomum',
                        'mof_status' => 'cursando'
                    ]);
                }else{
                    DB::rollBack();
                    return new JsonResponse("Aluno já matriculado nessa disciplina para esse periodo e turma", Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

            }

            DB::commit();
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
