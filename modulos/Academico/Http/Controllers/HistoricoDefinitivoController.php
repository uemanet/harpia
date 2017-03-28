<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\HistoricoDefinitivoRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\HistoricoDefinitivoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class HistoricoDefinitivoController extends BaseController
{
    private $cursoRepository;
    private $matriculaCursoRepository;
    private $historicoDefinitivoRepository;

    public function __construct(
        CursoRepository $cursoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        HistoricoDefinitivoRepository $historicoDefinitivoRepository
    ) {
        $this->cursoRepository = $cursoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->historicoDefinitivoRepository = $historicoDefinitivoRepository;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::historicodefinitivo.index', compact('cursos'));
    }

    public function postPrint(HistoricoDefinitivoRequest $request)
    {
        $matriculas = $request->only('matriculas');

        if (!empty($matriculas)) {
            $matr = $this->matriculaCursoRepository->find($matriculas['matriculas'][0])->first();

            $mpdf = new \mPDF();
            $mpdf->SetTitle('Histórico(s) Definitivo(s) - '. $matr->turma->ofertacurso->curso->crs_nome);

            foreach ($matriculas['matriculas'] as $id) {
                $matricula = $this->matriculaCursoRepository->find($id);

                if (!$matricula) {
                    flash()->error('Matricula não encontrada');
                    return redirect()->route('academico.historicodefinitivo.index');
                }

                if ($matricula->mat_situacao != 'concluido') {
                    flash()->error('Aluno não concluiu o curso!');
                    return redirect()->route('academico.historicodefinitivo.index');
                }

                $mpdf->AddPage('P');

                $dados = $this->historicoDefinitivoRepository->getGradeCurricularByMatricula($matricula->mat_id);

                $blade = 'graduacao';

                if ($matricula->turma->ofertacurso->curso->crs_nvc_id == 1) {
                    $blade = 'tecnico';
                }

                $mpdf->WriteHTML(view('Academico::historicodefinitivo.'.$blade, compact('dados'))->render());
            }

            $mpdf->Output('Historico_Defintivo.pdf', 'I');
        }
    }
}
