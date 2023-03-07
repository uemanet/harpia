<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modulos\Academico\Models\Noticia;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\NoticiaRepository;
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
    private $noticiaRepository;

    public function __construct(AlunoRepository $alunoRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                CursoRepository $cursoRepository,
                                TurmaRepository $turmaRepository,
                                NoticiaRepository $noticiaRepository)
    {
        $this->alunoRepository = $alunoRepository;
        $this->matriculaRepository = $matriculaCursoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->turmaRepository = $turmaRepository;
        $this->noticiaRepository = $noticiaRepository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(10);

        return view('Academico::index.index', [
            'alunos' => $this->alunoRepository->count(),
            'matriculas' => $this->matriculaRepository->count(),
            'cursos' => $this->cursoRepository->count(),
            'turmas' => $this->turmaRepository->count(),
            'noticias' => $noticias
        ]);
    }
}