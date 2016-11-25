<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;

class Turmas extends BaseController
{
    protected $turmaRepository;

    public function __construct(TurmaRepository $turma)
    {
        $this->turmaRepository = $turma;
    }

    public function getFindallbyofertacurso($idOfertaCurso)
    {
        $turmas = $this->turmaRepository->findAllByOfertaCurso($idOfertaCurso);

        return new JsonResponse($turmas, 200);
    }
}
