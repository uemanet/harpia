<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class Polos extends BaseController
{
    protected $poloRepository;
    protected $ofertaCursoRepository;

    public function __construct(PoloRepository $polo, OfertaCursoRepository $oferta)
    {
        $this->poloRepository = $polo;
        $this->ofertaCursoRepository = $oferta;
    }

    public function getFindallbyofertacurso($ofertaCursoId)
    {
        $oferta = $this->ofertaCursoRepository->find($ofertaCursoId);
        $polos = $oferta->polos;

        return new JsonResponse($polos, 200);
    }
}
