<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\Request;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\LancamentoTccRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LancamentosTccs extends BaseController
{
    protected $anexoRepository;
    protected $lancamentotccRepository;

    public function __construct(AnexoRepository $anexoRepository, LancamentoTccRepository $lancamentotccRepository)
    {
        $this->anexoRepository = $anexoRepository;
        $this->lancamentotccRepository = $lancamentotccRepository;
    }

    public function postDeletarAnexo(Request $request)
    {
        $lancamentotccId = $request->get('ltc_id');
        $lancamentotcc = $this->lancamentotccRepository->find($lancamentotccId);

        if (is_null($lancamentotcc->ltc_anx_tcc)) {
            return new JsonResponse('Sem anexos para serem exluidos!', Response::HTTP_BAD_REQUEST);
        }

        if ($this->lancamentotccRepository->deleteAnexoTcc($lancamentotccId)) {
            return new JsonResponse(Response::HTTP_OK);
        }
    }
}
