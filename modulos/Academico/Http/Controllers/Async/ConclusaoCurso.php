<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modulos\Academico\Events\ConclusaoCursoEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;
use DB;

class ConclusaoCurso extends BaseController
{
    protected $matriculaCursoRepository;

    public function __construct(MatriculaCursoRepository $matricula)
    {
        $this->matriculaCursoRepository = $matricula;
    }

    public function getAllalunosaptosounao(Request $request)
    {
        $dados = $request->all();

        $alunos = $this->matriculaCursoRepository->getAlunosAptosOrNot($dados['ofc_id'], $dados['trm_id'], $dados['pol_id']);

        return new JsonResponse($alunos, 200);
    }

    public function postConcluirMatriculas(Request $request)
    {
        $matriculas = $request->input('matriculas');
        $ofertaCursoId = $request->input('ofc_id');

        DB::beginTransaction();
        
        try {
            foreach ($matriculas as $matricula) {
                $result = $this->matriculaCursoRepository->concluirMatricula($matricula, $ofertaCursoId);

                if (!$result) {
                    DB::rollback();
                    return new JsonResponse('Matricula(s) não está apta para conclusão de curso', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                event(new ConclusaoCursoEvent($result, 'UPDATE_STATUS_CONCLUSAO'));
                
                DB::commit();
                return new JsonResponse("Matriculas concluidas com sucesso", 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                throw $e;
            }
            return new JsonResponse('Erro ao tentar concluir matriculas. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
