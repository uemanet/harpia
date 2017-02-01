<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modulos\Academico\Events\OfertaDisciplinaEvent;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;

class OfertaDisciplina extends BaseController
{
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $turmaRepository;

    public function __construct(OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                MatriculaOfertaDisciplinaRepository $matricula,
                                TurmaRepository $turmaRepository)
    {
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matricula;
        $this->turmaRepository = $turmaRepository;
    }

    public function getFindallbycurso($cursoId)
    {
        $ofertadisciplina = $this->ofertaDisciplinaRepository->findAllByCurso($cursoId);

        return new JsonResponse($ofertadisciplina, 200);
    }

    public function getFindall(Request $request)
    {
        $retorno = $this->ofertaDisciplinaRepository->findAll($request->all(), [
            'dis_id',
            'dis_nome',
            'dis_carga_horaria',
            'dis_creditos',
            'ofd_qtd_vagas',
            'ofd_id',
            'prf_id',
            'pes_nome'
        ]);

        for ($i = 0;$i < $retorno->count(); $i++) {
            $qtdMatriculas = $this->matriculaOfertaDisciplinaRepository->getMatriculasByOfertaDisciplina($retorno[$i]->ofd_id)->count();

            $retorno[$i]->qtdMatriculas = $qtdMatriculas;
        }

        return new JsonResponse($retorno, 200);
    }

    public function postOferecerdisciplina(Request $request)
    {
        try {
            if (!$this->ofertaDisciplinaRepository->verifyDisciplinaTurmaPeriodo($request->ofd_trm_id, $request->ofd_per_id, $request->ofd_mdc_id)) {
                $ofertadisciplina = $this->ofertaDisciplinaRepository->create($request->except('_token'));

                if (!$ofertadisciplina) {
                    return new JsonResponse('Erro ao tentar salvar', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $turma = $this->turmaRepository->find($ofertadisciplina->ofd_trm_id);

                if ($turma->trm_integrada) {
                    event(new OfertaDisciplinaEvent($ofertadisciplina));
                }

                return new JsonResponse($ofertadisciplina, Response::HTTP_OK);
            }

            return new JsonResponse('Disciplina já existente para esse periodo e turma.', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            return new JsonResponse('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function postDeletarofertadisciplina(Request $request)
    {
        $ofertaId = $request->input('ofd_id');

        $qtdMatriculas = $this->matriculaOfertaDisciplinaRepository->getMatriculasByOfertaDisciplina($ofertaId)->count();

        if ($qtdMatriculas) {
            return new JsonResponse('Não foi possivel deletar oferta. A mesma já possui alunos matriculados', Response::HTTP_BAD_GATEWAY, [], JSON_UNESCAPED_UNICODE);
        }

        if ($this->ofertaDisciplinaRepository->delete($ofertaId)) {
            return new JsonResponse(Response::HTTP_OK);
        }

        return new JsonResponse(Response::HTTP_BAD_REQUEST);
    }
}
