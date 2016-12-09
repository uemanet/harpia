<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Core\Http\Controller\BaseController;

class Disciplinas extends BaseController
{
    protected $disciplinaRepository;

    public function __construct(DisciplinaRepository $disciplina, ModuloMatrizRepository $modulomatriz)
    {
        $this->disciplinaRepository = $disciplina;
        $this->modulosmatrizesRepository = $modulomatriz;
    }

    public function getFindbynome($matrizId, $nome, $moduloId)
    {
        $disciplinas = array();

        $disciplinas['disciplinas'] = $this->disciplinaRepository->buscar($matrizId, $nome);

        if ($disciplinas['disciplinas']) {

            $disciplinas['prerequisitos'] = $this->disciplinaRepository->getDisciplinasModulosAnteriores($matrizId, $moduloId);

            return new JsonResponse($disciplinas, 200);
        }

        return new JsonResponse('Sem registros', 404);
    }
}
