<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Requests\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class HistoricoDefinitivoController extends BaseController
{
    private $cursoRepository;
    private $matriculaCursoRepository;

    public function __construct(
        CursoRepository $cursoRepository,
        MatriculaCursoRepository $matriculaCursoRepository
    ) {
        $this->cursoRepository = $cursoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::historicodefinitivo.index', compact('cursos'));
    }

    public function getPrint(Request $request)
    {
        $matriculaId = $request->input('mat_id');

        $matricula = $this->matriculaCursoRepository->find($matriculaId);

        if (!$matricula) {
            flash()->error('Matricula não encontrada');
            return redirect()->route('academico.historicodefinitivo.index');
        }

        if ($matricula->mat_situacao != 'concluido') {
            flash()->error('Aluno não concluiu o curso!');
            return redirect()->route('academico.historicodefinitivo.index');
        }
    }
}
