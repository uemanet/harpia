<?php

namespace Modulos\Seguranca\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\PermissaoRepository;

class Permissao extends BaseController
{
    protected $permissaoRepository;

    public function __construct(PermissaoRepository $permissaoRepository)
    {
        $this->permissaoRepository = $permissaoRepository;
    }

    public function getRecursosByModulo(Request $request)
    {
        $modulo = $request->get('modulo');

        $recursos = [];
        if ($modulo) {
            $recursos = $this->permissaoRepository->getRecursosByModulo($modulo);
        }

        return new JsonResponse($recursos, 200,[], JSON_UNESCAPED_UNICODE);
    }
}