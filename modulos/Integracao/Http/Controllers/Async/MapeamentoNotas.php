<?php

namespace Modulos\Integracao\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Events\MapearNotasEvent;
use Modulos\Integracao\Repositories\MapeamentoNotaRepository;

class MapeamentoNotas extends BaseController
{
    protected $mapeamentoNotaRepository;
    protected $matriculaOfertaDisciplinaRepository;

    public function __construct(
        MapeamentoNotaRepository $mapeamentoNotaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository
    ) {
        $this->mapeamentoNotaRepository = $mapeamentoNotaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
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
