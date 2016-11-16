<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\OfertaDisciplinaRequest;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class OfertasDisciplinasController extends BaseController
{
    protected $ofertadisciplinaRepository;
    protected $turmaRepository;
    protected $modulodisciplinaRepository;
    protected $professorRepository;
    protected $periodoletivoRepository;
    protected $cursoRepository;

    public function __construct(OfertaDisciplinaRepository $ofertadisciplinaRepository,
                                TurmaRepository $turmaRepository,
                                ModuloDisciplinaRepository $modulodisciplinaRepository,
                                ProfessorRepository $professorRepository,
                                PeriodoLetivoRepository $periodoletivoRepository,
                                CursoRepository $cursoRepository)
    {
        $this->ofertadisciplinaRepository = $ofertadisciplinaRepository;
        $this->turmaRepository = $turmaRepository;
        $this->modulodisciplinaRepository = $modulodisciplinaRepository;
        $this->professorRepository = $professorRepository;
        $this->periodoletivoRepository = $periodoletivoRepository;
        $this->cursoRepository = $cursoRepository;

    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');
        $periodoletivo = $this->periodoletivoRepository->lists('per_id', 'per_nome');

        return view('Academico::ofertasdisciplinas.index', compact('cursos', 'periodoletivo'));
    }

    public function getCreate()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');
        $professor = $this->professorRepository->lists('prf_id', 'pes_nome', true);
        $periodoletivo = $this->periodoletivoRepository->lists('per_id', 'per_nome');

        return view('Academico::ofertasdisciplinas.create', compact('cursos', 'professor', 'periodoletivo'));
    }

    public function postCreate(OfertaDisciplinaRequest $request)
    {
        try {
            if (!$this->ofertadisciplinaRepository->verifyDisciplinaTurmaPeriodo($request->ofd_trm_id, $request->ofd_per_id))
            {
                $ofertadisciplina = $this->ofertadisciplinaRepository->create($request->all());

                if (!$ofertadisciplina) {
                    flash()->error('Erro ao tentar salvar.');
                    return redirect()->back()->withInput($request->all());
                }
                flash()->success('Oferta de disciplina criada com sucesso');
                return redirect('/academico/ofertasdisciplinas/index');
            }

            flash()->error('Disciplina já existente para esse periodo e turma.');
            return redirect()->back()->withInput($request->all());

        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
