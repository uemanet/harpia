<?php

namespace Modulos\Academico\Http\Controllers;

use Mpdf\Mpdf;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\HistoricoDefinitivoRepository;
use Modulos\Academico\Http\Requests\HistoricoDefinitivoRequest;

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

            $mpdf = new Mpdf();

            $cursoNome = $matr->turma->ofertacurso->curso->crs_nome;
            $mpdf->SetTitle('Histórico(s) Definitivo(s) - '. $cursoNome);
            $mpdf->SetMargins(2, 2, 5);

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

                /*
                 * Niveis de Curso
                 *
                 * Graduação -> 1
                 * Técnico -> 2
                 * Tecnólogo -> 3
                 * Especialização -> 4
                 * Mestrado -> 5
                 * Doutorado -> 6
                 * Aperfeiçoamento -> 7
                 */

                $blade = '';

                switch ($matricula->turma->ofertacurso->curso->crs_nvc_id) {
                    case 2:
                        $blade = 'tecnico';
                        break;
                    case 4:
                        $blade = 'especializacao';
                        break;
                    default:
                        $blade = 'graduacao';
                        break;
                }

                $mpdf->WriteHTML(view('Academico::historicodefinitivo.'.$blade, compact('dados'))->render());
            }

            $mpdf->Output('Historico_Definitivo_'.str_replace(' ', '_', $cursoNome).'.pdf', 'I');
            exit;
        }

        flash()->error('Erro ao processar os dados. Entre em contato com o suporte');
        return redirect()->route('academico.historicodefinitivo.index');
    }
}
