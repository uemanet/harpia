<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Core\Http\Controller\BaseController;

class Grupos extends BaseController
{
    protected $grupoRepository;
    
    public function __construct(GrupoRepository $grupo)
    {
        $this->grupoRepository = $grupo;
    }
    
    public function getFindallbyturmapolo($turmaId, $poloId)
    {
        $grupos = $this->grupoRepository->getAllByTurmaAndPolo($turmaId, $poloId);

        return new JsonResponse($grupos, 200);
    }
}
