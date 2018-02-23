<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\OfertaCursoRepository;

class OfertasCursos extends BaseController
{
    protected $ofertaCursoRepository;

    public function __construct(OfertaCursoRepository $ofertaCursoRepository)
    {
        $this->ofertaCursoRepository = $ofertaCursoRepository;
    }

    public function getFindallbycurso($cursoId)
    {
        $ofertas = $this->ofertaCursoRepository->findAllByCurso($cursoId);

        return new JsonResponse($ofertas, 200);
    }

    public function getFindallbycursowithoutpresencial($cursoId)
    {
        $ofertas = $this->ofertaCursoRepository->findAllByCursoWithoutPresencial($cursoId);

        return new JsonResponse($ofertas, 200);
    }

    public function getFindAllByCursoWithoutEad($cursoId)
    {
        $ofertas = $this->ofertaCursoRepository->findAllByCursoWithoutEad($cursoId);

        return new JsonResponse($ofertas, 200);
    }
}
