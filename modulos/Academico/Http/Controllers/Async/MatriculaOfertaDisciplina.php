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

    public function postMatricularAlunoDisciplinas(Request $request)
    {
        $ofertas = $request->input('ofertas');
        $matriculaId = $request->input('mof_mat_id');

        DB::beginTransaction();

        try {
            foreach ($ofertas as $ofertaId) {

                $result = $this->matriculaOfertaDisciplinaRepository->createMatricula(['mat_id' => $matriculaId, 'ofd_id' => $ofertaId]);

                if($result['type'] == 'error') {
                    DB::rollback();
                    return new JsonResponse($result['message'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
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
