<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\TurmaRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;

class TurmasController extends BaseController
{
    protected $turmaRepository;
    protected $cursoRepository;
    protected $ofertacursoRepository;
    protected $periodoletivoRepository;

    public function __construct(TurmaRepository $turmaRepository, CursoRepository $cursoRepository, OfertaCursoRepository $ofertacursoRepository, PeriodoLetivoRepository $periodoletivoRepository)
    {
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->ofertacursoRepository = $ofertacursoRepository;
        $this->periodoletivoRepository = $periodoletivoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/turmas/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->turmaRepository->paginateRequest($request->all());

        $tabela = $tableData->columns(array(
            'trm_id' => '#',
            'trm_nome' => 'Turma',
            'trm_ofc_id' => 'Oferta',
            'trm_per_id' => 'Período Letivo',
            'trm_qtd_vagas' => 'Vagas',
            'trm_action' => 'Ações'
        ))
            ->modifyCell('trm_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('trm_action', 'trm_id')
            ->modify('trm_action', function ($id) {
                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-pencil',
                            'action' => '/academico/turmas/edit/' . $id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'action' => '/academico/turmas/delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('trm_id', 'trm_nome'));

        $paginacao = $tableData->appends($request->except('page'));

        return view('Academico::turmas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        //$ofertascursos = $this->ofertacursoRepository->lists('ofc_id', 'ofc_ano');

        $periodosletivos = $this->periodoletivoRepository->lists('per_id', 'per_nome');

        return view('Academico::turmas.create', compact('cursos', 'periodosletivos'));
    }

    public function postCreate(TurmaRequest $request)
    {
        try {
            $turma = $this->turmaRepository->create($request->all());

            if (!$turma) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Turma criada com sucesso.');

            return redirect('/academico/turmas');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
  }
