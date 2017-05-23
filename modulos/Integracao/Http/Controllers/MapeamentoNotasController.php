<?php

namespace Modulos\Integracao\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\MapeamentoNotaRepository;
use Validator;

class MapeamentoNotasController extends BaseController
{
    protected $cursoRepository;
    protected $mapeamentoNotasRepository;
    protected $turmaRepository;

    public function __construct(
        CursoRepository $cursoRepository,
        MapeamentoNotaRepository $mapeamentoNotaRepository,
        TurmaRepository $turmaRepository
    ) {
        $this->cursoRepository = $cursoRepository;
        $this->mapeamentoNotasRepository = $mapeamentoNotaRepository;
        $this->turmaRepository = $turmaRepository;
    }

    public function index(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        if ($request->getMethod() == 'POST') {
            $rules = [
                'crs_id' => 'required',
                'ofc_id' => 'required',
                'trm_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->route('integracao.mapeamentonotas.index')->withErrors($validator);
            }

            $turmaId = $request->input('trm_id');

            $turma = $this->turmaRepository->find($turmaId);

            if (!$turma) {
                flash()->error('Turma inexistente!');
                return redirect()->back();
            }

            $ofertas = $this->mapeamentoNotasRepository->getGradeCurricularByTurma($turmaId);

            return view('Integracao::mapeamentonotas.index', compact('cursos', 'ofertas', 'turma'));
        }

        return view('Integracao::mapeamentonotas.index', compact('cursos'));
    }
}
