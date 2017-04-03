<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modulos\Academico\Events\DeleteOfertaDisciplinaEvent;
use Modulos\Academico\Events\OfertaDisciplinaEvent;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use DB;

class OfertaDisciplina extends BaseController
{
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $turmaRepository;
    protected $ambienteRepository;

    public function __construct(OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                MatriculaOfertaDisciplinaRepository $matricula,
                                TurmaRepository $turmaRepository,
                                AmbienteVirtualRepository $ambienteRepository)
    {
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matricula;
        $this->turmaRepository = $turmaRepository;
        $this->ambienteRepository = $ambienteRepository;
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
            'pes_nome',
        ]);

        for ($i = 0; $i < $retorno->count(); ++$i) {
            $qtdMatriculas = $this->matriculaOfertaDisciplinaRepository->getMatriculasByOfertaDisciplina($retorno[$i]->ofd_id)->count();

            $retorno[$i]->qtdMatriculas = $qtdMatriculas;
        }

        return new JsonResponse($retorno, 200);
    }

    public function postOferecerdisciplina(Request $request)
    {
        try {
            if (!$this->ofertaDisciplinaRepository->verifyDisciplinaTurmaPeriodo($request->ofd_trm_id, $request->ofd_per_id, $request->ofd_mdc_id)) {
                if ($request->input('ofd_qtd_vagas') < 1) {
                    return new JsonResponse('Quantidade de vagas insuficiente', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $ofertadisciplina = $this->ofertaDisciplinaRepository->create($request->except('_token'));

                if (!$ofertadisciplina) {
                    return new JsonResponse('Erro ao tentar salvar', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $turma = $this->turmaRepository->find($ofertadisciplina->ofd_trm_id);

                if ($turma->trm_integrada) {
                    event(new OfertaDisciplinaEvent($ofertadisciplina, "CREATE"));
                }

                return new JsonResponse($ofertadisciplina, Response::HTTP_OK);
            }use Modulos\Integracao\Repositories\AmbienteVirtualRepository;


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

        try {
            DB::beginTransaction();

            $oferta = $this->ofertaDisciplinaRepository->find($ofertaId);
            $turma = $this->turmaRepository->find($oferta->ofd_trm_id);

            $this->ofertaDisciplinaRepository->delete($ofertaId);

            $ambiente = $this->ambienteRepository->getAmbienteByTurma($turma->trm_id);

            if ($ambiente) {
              event(new DeleteOfertaDisciplinaEvent($oferta, "DELETE", $ambiente->id));
            }

            DB::commit();
            return new JsonResponse('Disciplina excluída com sucesso', JsonResponse::HTTP_OK,  [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            DB::rollback();

            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse('Não foi possível excluir a disciplina', JsonResponse::HTTP_INTERNAL_SERVER_ERROR, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
