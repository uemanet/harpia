<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;

class LancamentoNotas extends BaseController
{
    protected $cursoRepository;

    public function __construct(CursoRepository $cursoRepository)
    {
        $this->cursoRepository = $cursoRepository;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::lancamentonotas.index', [
            'cursos' => $cursos
        ]);
    }
}
