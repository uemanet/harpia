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
        $ofertaId = $request->input('ofertaId');
        //dd($id);

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/turmas/create?ofertaId='.$ofertaId)->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->turmaRepository->paginateRequestByOferta($ofertaId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'trm_id' => '#',
                'trm_ofc_id' => 'Ano da oferta',
                'trm_per_id' => 'Periodo Letivo',
                'trm_nome' => 'Turma',
                'trm_qtd_vagas' => 'Quantidade de Vagas',
                'trm_action' => 'Ações'
            ))
                ->modifyCell('trm_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('trm_action', 'trm_id')
                ->means('trm_ofc_id', 'ofertacurso')
                ->modify('trm_ofc_id', function ($ofertacurso) {
                  return $ofertacurso->ofc_ano;
                })
                ->means('trm_per_id', 'periodo')
                ->modify('trm_per_id', function ($periodo) {
                  return $periodo->per_nome;
                })
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
                                'action' => '/academico/turmas/edit?turmaId='.$id,
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
        }



        return view('Academico::turmas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate(Request $request)
    {

        $ofertaId = $request->input('ofertaId');

        $oferta = $this->ofertacursoRepository->find($ofertaId);

        $curso = $this->cursoRepository->listsCursoByOferta($oferta->ofc_crs_id);

        $oferta = $this->ofertacursoRepository->listsAllById($ofertaId);

        $periodosletivos = $this->periodoletivoRepository->lists('per_id', 'per_nome');

        return view('Academico::turmas.create', compact('curso', 'periodosletivos', 'oferta'));
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

            return redirect('/academico/turmas/index?ofertaId='.$turma->trm_ofc_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit(Request $request)
    {
        $turmaId = $request->input('turmaId');

        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
            flash()->error('Recurso não existe.');

            return redirect()->back();
        }

        $oferta = $this->ofertacursoRepository->find($turma->trm_ofc_id);

        $curso = $this->cursoRepository->listsCursoByOferta($oferta->ofc_crs_id);

        $oferta = $this->ofertacursoRepository->listsAllById($turma->trm_ofc_id);
        //dd($oferta);
        $periodosletivos = $this->periodoletivoRepository->lists('per_id', 'per_nome');

        return view('Academico::turmas.edit', compact('turma', 'curso', 'oferta', 'periodosletivos'));
    }

    public function putEdit($id, TurmaRequest $request)
    {
        try {
            $turma = $this->turmaRepository->find($id);

            if (!$turma) {
                flash()->error('Turma não existe.');

                return redirect('/academico/turmas');
            }

            $requestData = $request->only($this->turmaRepository->getFillableModelFields());

            if (!$this->turmaRepository->update($requestData, $turma->trm_id, 'trm_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Turma atualizada com sucesso.');

            return redirect('/academico/turmas/index?ofertaId='.$turma->trm_ofc_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $turmaId = $request->get('id');

            if ($this->turmaRepository->delete($turmaId)) {
                flash()->success('Turma excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o turma');
            }

            return redirect()->back();
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
