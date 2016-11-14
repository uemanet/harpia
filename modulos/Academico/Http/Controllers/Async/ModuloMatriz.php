<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Core\Http\Controller\BaseController;

class ModuloMatriz extends BaseController
{
    protected $moduloMatrizRepository;

    public function __construct(ModuloMatrizRepository $repository)
    {
        $this->moduloMatrizRepository = $repository;
    }

    public function getFindallbymatriz($matrizId)
    {
        $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($matrizId);

        return new JsonResponse($modulos, 200);
    }
}