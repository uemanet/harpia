<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Core\Http\Controller\BaseController;

class PeriodoLetivo extends BaseController
{
    protected $periodoLetivoRepository;

    public function __construct(PeriodoLetivoRepository $periodo)
    {
        $this->periodoLetivoRepository = $periodo;
    }

    public function getFindallbyturma($turmaId)
    {
        $periodos = $this->periodoLetivoRepository->getAllByTurma($turmaId);

        return new JsonResponse($periodos, 200);
    }
}
