<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;

class OfertaDisciplina extends BaseController
{
    protected $ofertaDisciplinaRepository;

    public function __construct(OfertaDisciplinaRepository $ofertaDisciplinaRepository)
    {
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
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
            'prf_id',
            'pes_nome'
        ]);

        return new JsonResponse($retorno, 200);
    }

    public function postOferecerdisciplina(Request $request)
    {
        try {
            if (!$this->ofertaDisciplinaRepository->verifyDisciplinaTurmaPeriodo($request->ofd_trm_id, $request->ofd_per_id, $request->ofd_mdc_id))
            {
                $ofertadisciplina = $this->ofertaDisciplinaRepository->create($request->except('_token'));

                if (!$ofertadisciplina) {
                    return new JsonResponse('Erro ao tentar salvar', Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
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
}
