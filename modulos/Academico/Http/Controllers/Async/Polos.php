<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Core\Http\Controller\BaseController;

class Polos extends BaseController
{
    protected $poloRepository;

    public function __construct(PoloRepository $polo)
    {
        $this->poloRepository = $polo;
    }

    public function getFindallbyofertacurso($idOfertaCurso)
    {
        $polos = $this->poloRepository->findAllByOfertaCurso($idOfertaCurso);

        return new JsonResponse($polos, 200);
    }
}
