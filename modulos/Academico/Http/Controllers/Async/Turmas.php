<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Integracao\Models\AmbienteTurma;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class Turmas extends BaseController
{
    protected $turmaRepository;
    protected $ambientevirtualRepository;

    public function __construct(TurmaRepository $turma, AmbienteVirtualRepository $ambiente)
    {
        $this->turmaRepository = $turma;
        $this->ambientevirtualRepository = $ambiente;
    }

    public function getFindallbyofertacurso($idOfertaCurso)
    {
        $turmas = $this->turmaRepository->findAllByOfertaCurso($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }

    public function getFindallbyofertacursoIntegrada($idOfertaCurso)
    {
        $turmas = $this->turmaRepository->findAllByOfertaCursoIntegrada($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }

    public function getFindallbyofertacursoNaoIntegrada($idOfertaCurso)
    {
        $turmas = $this->turmaRepository->findAllByOfertaCursoNaoIntegrada($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }

    public function getFindallwithvagasdisponiveis($ofertaCursoId)
    {
        $turmas = $this->turmaRepository->findAllWithVagasDisponiveisByOfertaCurso($ofertaCursoId);

        return new JsonResponse($turmas, 200);
    }

    public function getFindallbyofertacursoWithoutAmbiente($idOfertaCurso)
    {
        $turmas = $this->turmaRepository->all();

        $ids = AmbienteTurma::all()->pluck('atr_trm_id')->toArray();

        $turmasSemAmbiente = $turmas->filter(function ($turma) use ($ids, $idOfertaCurso) {
            if (in_array($turma->trm_id, $ids) && $turma->trm_ofc_id == $idOfertaCurso && $turma->trm_integrada == 1) {
                return $turma;
            }
        });

        return new JsonResponse($turmasSemAmbiente, 200);
    }
}
