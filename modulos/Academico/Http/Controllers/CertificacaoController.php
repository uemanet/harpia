<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\LivroRepository;
use Modulos\Academico\Repositories\RegistroRepository;

class CertificacaoController
{
    protected $livroRepository;
    protected $registroRepository;
    protected $cursoRepository;
    protected $matriculacursoRepository;

    public function __construct(LivroRepository $livroRepository,
                                RegistroRepository $registroRepository,
                                CursoRepository $cursoRepository,
                                MatriculaCursoRepository $matriculacursoRepository)
    {
        $this->livroRepository = $livroRepository;
        $this->registroRepository = $registroRepository;
        $this->cursoRepository = $cursoRepository;
        $this->matriculacursoRepository = $matriculacursoRepository;
    }

    public function getIndex(Request $request)
    {
        $cursosTecnicos = $this->cursoRepository->listsCursosTecnicos();


        return view('Academico::certificacao.index', [
            'cursos' => $cursosTecnicos
        ]);
    }

    public function getPrint($idMatricula, $idModulo)
    {
        $dados = $this->matriculacursoRepository->getPrintData($idMatricula, $idModulo);

        if (!$dados) {
            flash()->error('Esse registro não existe.');
            return redirect()->back();
        }
        define('_MPDF_TTFONTDATAPATH', sys_get_temp_dir()."/");
        $mpdf = new \mPDF();
        $mpdf->addPage('L');
        $mpdf->WriteHTML(view('Academico::certificacao.print', compact('dados'))->render());
        $mpdf->Output();
    }
}
