<?php

namespace Modulos\Seguranca\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\RecursoRepository;

class Recursos extends BaseController
{
    protected $recursoRepository;

    public function __construct(RecursoRepository $recursoRepository)
    {
        $this->recursoRepository = $recursoRepository;
    }

    public function getFindallbymodulo($moduloId)
    {
        $recursos = $this->recursoRepository->findAllByModulo($moduloId);

        return new JsonResponse($recursos, 200);
    }
}
