<?php

namespace Modulos\Seguranca\Http\Controllers\Ajax;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\CategoriaRecursoRepository;

class CategoriasRecursos extends BaseController
{
    protected $categoriaRecursoRepository;

    public function __construct(CategoriaRecursoRepository $categoriaRecursoRepository)
    {
        $this->categoriaRecursoRepository = $categoriaRecursoRepository;
    }

    public function getFindallbymodulo($moduloId)
    {
        $categorias = $this->categoriaRecursoRepository->findAllByModulo($moduloId);

        return new JsonResponse($categorias, 200);
    }
}
