<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Core\Http\Controller\BaseController;

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

    public function getFindallwithvagasdisponiveis($ofertaCursoId)
    {
        $turmas = $this->turmaRepository->findAllWithVagasDisponiveisByOfertaCurso($ofertaCursoId);

        return new JsonResponse($turmas, 200);
    }

    public function getFindallbyofertacursoWithoutAmbiente($idOfertaCurso)
    {
        $turmas = $this->ambientevirtualRepository->findTurmasWithoutAmbiente($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }
}
