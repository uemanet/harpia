<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;

class MatrizesCurriculares extends BaseController
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
}
