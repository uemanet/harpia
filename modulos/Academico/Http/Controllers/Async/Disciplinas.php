<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Core\Http\Controller\BaseController;

class Disciplinas extends BaseController
{
    protected $disciplinsRepository;

    public function __construct(DisciplinaRepository $disciplina, ModuloMatrizRepository $modulomatriz)
    {
        $this->disciplinaRepository = $disciplina;
        $this->modulosmatrizesRepository = $modulomatriz;
    }

    public function getFindbynome($matriz, $nome)
    {
        $disciplinas = $this->disciplinaRepository->buscar($matriz, $nome);

        if($disciplinas)
        {
            return new JsonResponse($disciplinas, 200);
        }

        return new JsonResponse('Sem registros', 404);
    }
}
