<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Core\Http\Controller\BaseController;

class Professor extends BaseController
{
    protected $professorRepository;
    
    public function __construct(ProfessorRepository $professor)
    {
        $this->professorRepository = $professor;
    }
    
    public function getFindall()
    {
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        return new JsonResponse($professores, 200);
    }
}