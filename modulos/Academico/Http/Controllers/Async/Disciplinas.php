<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;

class Disciplinas extends BaseController
{
    protected $disciplinasRepository;

    public function __construct(DisciplinaRepository $disciplina)
    {
        $this->disciplinasRepository = $disciplina;
    }

    public function getFindbynome($nome)
    {
        $disciplinas = $this->disciplinasRepository->buscar($nome);

        if($disciplinas)
        {
            return new JsonResponse($disciplinas, 200);
        }

        return new JsonResponse('Sem registros', 404);
    }
}