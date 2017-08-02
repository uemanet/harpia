<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Events\UpdateProfessorDisciplinaEvent;
use Modulos\Academico\Events\DeleteOfertaDisciplinaEvent;
use Modulos\Academico\Http\Requests\OfertaDisciplinaRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use DB;

class OfertasDisciplinasController extends BaseController
{
    protected $ofertadisciplinaRepository;
    protected $turmaRepository;
    protected $professorRepository;
    protected $cursoRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(OfertaDisciplinaRepository $ofertadisciplinaRepository,
                                TurmaRepository $turmaRepository,
                                ProfessorRepository $professorRepository,
                                CursoRepository $cursoRepository,
                                MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->ofertadisciplinaRepository = $ofertadisciplinaRepository;
        $this->turmaRepository = $turmaRepository;
        $this->professorRepository = $professorRepository;
        $this->cursoRepository = $cursoRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function getIndex()
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Ofertar Disciplinas')->setRoute('academico.ofertasdisciplinas.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButton[] = $btnNovo;

        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::ofertasdisciplinas.index', compact('cursos', 'periodoletivo', 'actionButton'));
    }

    public function getCreate()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');
        $professor = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        return view('Academico::ofertasdisciplinas.create', compact('cursos', 'professor'));
    }

    public function getEdit($ofertaDisciplinaId)
    {
        $ofertaDisciplina = $this->ofertadisciplinaRepository->find($ofertaDisciplinaId);

        if (!$ofertaDisciplina) {
            flash()->error('Oferta de Disciplina não existe.');
            return redirect()->back();
        }

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        return view('Academico::ofertasdisciplinas.edit', compact('ofertaDisciplina', 'professores'));
    }

    public function putEdit($ofertaDisciplinaId, OfertaDisciplinaRequest $request)
    {
        $ofertaDisciplina = $this->ofertadisciplinaRepository->find($ofertaDisciplinaId);

        if (!$ofertaDisciplina) {
            flash()->error('Oferta de Disciplina não existe.');
            return redirect()->back();
        }

        try {
            $oldProfessor = $ofertaDisciplina->ofd_prf_id;
            $ofertaDisciplina->fill($request->all())->save();

            if ($ofertaDisciplina->turma->trm_integrada && ($ofertaDisciplina->ofd_prf_id != $oldProfessor)) {
                event(new UpdateProfessorDisciplinaEvent($ofertaDisciplina));
            }

            flash()->success('Oferta de Disciplina atualizada com sucesso.');

            return redirect()->route('academico.ofertasdisciplinas.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $ofertaDisciplinaId = $request->get('id');

            $ofertaDisciplina = $this->ofertadisciplinaRepository->find($ofertaDisciplinaId);

            $qtdMatriculas = $this->matriculaOfertaDisciplinaRepository->getQuantMatriculasByOfertaDisciplina($ofertaDisciplinaId);

            if ($qtdMatriculas) {
                flash()->error('Não foi possivel deletar oferta. A mesma possui alunos matriculados');
                return redirect()->back();
            }

            DB::beginTransaction();

            $turma = $this->turmaRepository->find($ofertaDisciplina->ofd_trm_id);

            $this->ofertadisciplinaRepository->delete($ofertaDisciplinaId);

            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

            if ($turma->trm_integrada && $ambiente) {
                event(new DeleteOfertaDisciplinaEvent($ofertaDisciplina, "DELETE", $ambiente->id));
            }

            DB::commit();

            flash()->success('Oferta de Disciplina excluída com sucesso');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            flash()->error('Erro ao tentar deletar. A oferta de disciplina contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
