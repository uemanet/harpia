<?php

namespace Modulos\Seguranca\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\PerfilRepository;

class Perfis extends BaseController
{
    protected $perfisRepository;

    public function __construct(PerfilRepository $perfil)
    {
        $this->perfisRepository = $perfil;
    }

    public function getFindallbymodulo($moduloId)
    {
        $perfis = $this->perfisRepository->getAllByModulo($moduloId);

        return new JsonResponse($perfis, 200);
    }
}
