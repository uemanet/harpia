<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class ConclusaoCursoController extends BaseController
{
    protected $cursoRepository;

    public function __construct(CursoRepository $curso)
    {
        $this->cursoRepository = $curso;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::conclusaocurso.index', compact('cursos'));
    }
}
