<?php

namespace Modulos\Integracao\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\MapeamentoNotaRepository;

class MapeamentoNotas extends BaseController
{
    protected $mapeamentoNotaRepository;

    public function __construct(MapeamentoNotaRepository $mapeamentoNotaRepository)
    {
        $this->mapeamentoNotaRepository = $mapeamentoNotaRepository;
    }

    public function setMapeamentoNotas(Request $request)
    {
        $dados = json_decode($request->get('data'), true);

        $response = $this->mapeamentoNotaRepository->setMapeamentoNotas($dados);

        if (array_key_exists('error', $response)) {
            return new JsonResponse($response, 400, [], JSON_UNESCAPED_UNICODE);
        }

        return new JsonResponse($response, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
