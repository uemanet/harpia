<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Core\Http\Controller\BaseController;

class Tutores extends BaseController
{
    protected $tutorRepository;

    public function __construct(TutorRepository $tutor)
    {
        $this->tutorRepository = $tutor;
    }

    public function getFindallbygrupo($idGrupo)
    {
        $tutores = $this->tutorRepository->findAllByGrupo($idGrupo);

        return new JsonResponse($tutores, 200);
    }
}
