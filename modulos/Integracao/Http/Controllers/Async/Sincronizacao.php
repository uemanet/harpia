<?php

namespace Modulos\Integracao\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class Sincronizacao extends BaseController
{
    protected $sincronizacaoRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
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
            $sincronizacao = $this->sincronizacaoRepository->find($sync);

            if ($sincronizacao->sym_status == 2) {
                continue;
            }

            $this->sincronizacaoRepository->migrar($sync);

            $sincronizacao = $this->sincronizacaoRepository->find($sync);

            if ($sincronizacao->sym_status != 2) {
                $todasmigradas = false;
            }
            if ($sincronizacao->sym_status == 2) {
                $nenhumamigrada = false;
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
