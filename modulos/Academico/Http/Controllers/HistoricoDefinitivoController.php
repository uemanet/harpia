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
//        dd($matriculas);

        if (!empty($matriculas)) {
            foreach ($matriculas as $id) {
                $matricula = $this->matriculaCursoRepository->find($id)->first();

                if (!$matricula) {
                    flash()->error('Matricula não encontrada');
                    return redirect()->route('academico.historicodefinitivo.index');
                }

                if ($matricula->mat_situacao != 'concluido') {
                    flash()->error('Aluno não concluiu o curso!');
                    return redirect()->route('academico.historicodefinitivo.index');
                }

                $dados = $this->historicoDefinitivoRepository->getGradeCurricularByMatricula($matricula->mat_id);

                dd($dados);
            }
        }
    }
}
