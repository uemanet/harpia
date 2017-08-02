<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\LivroRepository;
use Modulos\Academico\Repositories\RegistroRepository;
use Modulos\Academico\Repositories\DiplomaRepository;

class DiplomasController
{
    protected $livroRepository;
    protected $registroRepository;
    protected $cursoRepository;
    protected $matriculacursoRepository;
    protected $diplomaRepository;

    public function __construct(LivroRepository $livroRepository,
                                RegistroRepository $registroRepository,
                                CursoRepository $cursoRepository,
                                MatriculaCursoRepository $matriculacursoRepository,
                                DiplomaRepository $diplomaRepository)
    {
        $this->livroRepository = $livroRepository;
        $this->registroRepository = $registroRepository;
        $this->cursoRepository = $cursoRepository;
        $this->matriculacursoRepository = $matriculacursoRepository;
        $this->diplomaRepository = $diplomaRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::diplomas.index', compact('cursos'));
    }

    public function postPrint(Request $request)
    {
        $retorno = $this->diplomaRepository->getPrintData($request['diplomas']);

        if (!$retorno) {
            flash()->error('Esse registro não existe.');
            return redirect()->back();
        }

        $mpdf = new \mPDF();
        $mpdf->addPage('L', '', '', '', '');

        $mpdf->WriteHTML(view('Academico::diplomas.print', ['retorno' => $retorno])->render());
        $mpdf->Output();
    }
}
