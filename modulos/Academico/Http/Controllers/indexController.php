<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;

/**
 * Class IndexController.
 */
class indexController extends Controller
{
    private $alunoRepository;
    private $matriculaRepository;
    private $cursoRepository;
    private $turmaRepository;

    public function __construct(AlunoRepository $alunoRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                CursoRepository $cursoRepository,
                                TurmaRepository $turmaRepository)
    {
        $this->alunoRepository = $alunoRepository;
        $this->matriculaRepository = $matriculaCursoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->turmaRepository = $turmaRepository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('Academico::index.index', [
            'alunos' => $this->alunoRepository->count(),
            'matriculas' => $this->matriculaRepository->count(),
            'cursos' => $this->cursoRepository->count(),
            'turmas' => $this->turmaRepository->count(),
        ]);
    }
}