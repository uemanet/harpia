<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class MatriculasLoteController extends BaseController
{
    protected $cursoRepository;

    public function __construct(CursoRepository $curso)
    {
        $this->cursoRepository = $curso;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::matriculaslote.index', compact('cursos'));
    }
}