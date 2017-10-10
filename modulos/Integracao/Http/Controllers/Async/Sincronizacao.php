<?php

namespace Modulos\Integracao\Http\Controllers\Async;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Harpia\Event\SincronizacaoFactory;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class Sincronizacao extends BaseController
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function getAll()
    {
        try {
            $headers = ["content-type" => "application/json"];
            return new JsonResponse($this->sincronizacaoRepository->all(), 200, $headers, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            return new JsonResponse("Erro ao processar requisicao", 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function postSincronizar(Request $request)
    {
        if (!array_key_exists('ids', $request->all())) {
            return new JsonResponse('Nenhum registro com erro foi enviado', 400, [], JSON_UNESCAPED_UNICODE);
        }
        $sincronizacaoIds = $request->all()['ids'];

        $todasmigradas = true;
        $nenhumamigrada = true;

        foreach ($sincronizacaoIds as $sync) {
            try {
                $sincronizacao = $this->sincronizacaoRepository->find($sync);

                if (!$sincronizacao) {
                    continue;
                }

                if ($sincronizacao->sym_status == 2) {
                    continue;
                }

                $event = SincronizacaoFactory::factory($sincronizacao);
                event($event); // Dispara event

                $nenhumamigrada = false;
            } catch (\Exception $e) {
                if (config('app.debug')) {
                    throw $e;
                }

                $todasmigradas = false;
                continue;
            }
        }

        if ($nenhumamigrada) {
            return new JsonResponse('Nenhum registro foi migrado', 400, [], JSON_UNESCAPED_UNICODE);
        }
        if (!$todasmigradas) {
            return new JsonResponse('Alguns registros não foram migrados', 200, [], JSON_UNESCAPED_UNICODE);
        }
        return new JsonResponse('Todas os registros foram migrados', 200, [], JSON_UNESCAPED_UNICODE);
    }
}
