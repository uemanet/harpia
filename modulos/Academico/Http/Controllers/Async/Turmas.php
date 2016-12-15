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
        $this->ambienteRepository = $ambiente;
    }

    public function getFindallbyofertacurso($idOfertaCurso)
    {
        $turmas = $this->turmaRepository->findAllByOfertaCurso($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }

    public function getFindallbyofertacursoWithoutAmbiente($idOfertaCurso)
    {
        $turmas = $this->ambienteRepository->findTurmasWithoutAmbiente($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }
}
