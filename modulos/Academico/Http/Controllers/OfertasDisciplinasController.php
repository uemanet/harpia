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
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/ofertasdisciplinas/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->ofertadisciplinaRepository->paginateRequest($request->all());
        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'ofd_id' => '#',
                'ofd_mdc_id' => 'Disciplina',
                'ofd_per_id' => 'Periodo Letivo',
                'ofd_action' => 'Ações'
            ))
                ->modifyCell('ofc_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('ofd_action', 'ofd_id')
                ->means('ofd_mdc_id', 'modulosDisciplinas')
                ->modify('ofd_mdc_id', function ($modulosDisciplinas) {
                    return $modulosDisciplinas->disciplina->dis_nome;
                })
                ->means('ofd_per_id', 'periodoletivo')
                ->modify('ofd_per_id', function ($periodoletivo) {
                    return $periodoletivo->per_nome;
                })
                ->modify('ofd_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-plus',
                                'action' => '#'.$id,
                                'label' => '#',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('ofd_id', 'ofd_mdc_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::ofertasdisciplinas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
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


            $ofertadisciplina = $this->ofertadisciplinaRepository->create($request->all());



//            $oferta = $this->ofertadisciplinaRepository->find($ofertadisciplina->ofc_id);
//
//            if (!is_null($request->polos)) {
//                foreach ($request->polos as $key => $polo) {
//                    $oferta->polos()->attach($polo);
//                }
//            }

            if (!$ofertadisciplina) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Oferta de disciplina criada com sucesso.');
            return redirect('/academico/ofertasdisciplinas/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
