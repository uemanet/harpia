<?php

namespace Modulos\Geral\Http\Controllers\Async;

use Illuminate\Http\Request;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\DocumentoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Documentos extends BaseController
{
    protected $anexoRepository;
    protected $documentoRepository;

    public function __construct(AnexoRepository $anexoRepository, DocumentoRepository $documentoRepository)
    {
        $this->anexoRepository = $anexoRepository;
        $this->documentoRepository = $documentoRepository;
    }

    public function postDeletarAnexo(Request $request)
    {
        $documentoId = $request->get('doc_id');
        $documento = $this->documentoRepository->find($documentoId);

        if (is_null($documento->doc_anx_documento)) {
            return new JsonResponse('Sem anexos para serem exluidos!', Response::HTTP_BAD_REQUEST);
        }

        if ($this->documentoRepository->deleteDocumento($documentoId)) {
            return new JsonResponse(Response::HTTP_OK);
        }
    }
}
