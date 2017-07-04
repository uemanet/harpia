<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Events\AtualizarTurmaEvent;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\TurmaRequest;
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

    public function getIndex($ofertaId, Request $request)
    {
        $ofertacurso = $this->ofertacursoRepository->find($ofertaId);

        if (!$ofertacurso) {
            flash()->error('Oferta não existe');

            return redirect()->back();
        }

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.ofertascursos.turmas.create')->setParameters(['id' => $ofertaId])->setIcon('fa fa-plus')->setStyle('btn bg-olive');


        $actionButtons[] = $btnNovo;
        $paginacao = null;
        $tabela = null;

        $tableData = $this->turmaRepository->paginateRequestByOferta($ofertaId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'trm_id' => '#',
                'trm_per_id' => 'Periodo Letivo',
                'trm_nome' => 'Turma',
                'trm_qtd_vagas' => 'Quantidade de Vagas',
                'trm_integrada_string' => 'Integrada',
                'trm_action' => 'Ações'
            ))
                ->modifyCell('trm_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('trm_action', 'trm_id')
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
                                'icon' => 'fa fa-group',
                                'route' => 'academico.ofertascursos.turmas.grupos.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Grupos',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'route' => 'academico.ofertascursos.turmas.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.ofertascursos.turmas.delete',
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

        return view('Academico::turmas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'ofertacurso' => $ofertacurso]);
    }

    public function getCreate(Request $request)
    {
        $ofertaId = $request->get('id');

        $oferta = $this->ofertacursoRepository->find($ofertaId);

        if (!$oferta) {
            flash()->error('Oferta não existe');

            return redirect()->back();
        }

        $curso = $this->cursoRepository->listsCursoByOferta($oferta->ofc_crs_id);

        $periodosletivos = $this->periodoletivoRepository->getPeriodosValidos($oferta->ofc_ano);

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
            return redirect()->route('academico.ofertascursos.turmas.index', $turma->trm_ofc_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($turmaId, Request $request)
    {
        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
            flash()->error('Recurso não existe.');
            return redirect()->back();
        }

        $oferta = $this->ofertacursoRepository->find($turma->trm_ofc_id);

        $curso = $this->cursoRepository->listsCursoByOferta($oferta->ofc_crs_id);

        $periodosletivos = $this->periodoletivoRepository->getPeriodosValidos($oferta->ofc_ano, $turma->trm_per_id);

        return view('Academico::turmas.edit', compact('turma', 'curso', 'oferta', 'periodosletivos'));
    }

    public function putEdit($id, TurmaRequest $request)
    {
        try {
            $turma = $this->turmaRepository->find($id);

            if (!$turma) {
                flash()->error('Turma não existe.');
                return redirect()->route('academico.ofertascursos.turmas.index', $id);
            }

            $requestData = $request->except('_token', '_method', 'trm_integrada');

            if (!$this->turmaRepository->update($requestData, $turma->trm_id, 'trm_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Turma atualizada com sucesso.');

            $turmaUpdated = $this->turmaRepository->find($id);
            if ($turmaUpdated->trm_integrada) {
                event(new AtualizarTurmaEvent($turmaUpdated, 'UPDATE'));
            }
            return redirect()->route('academico.ofertascursos.turmas.index', $turma->trm_ofc_id);
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
            $turmaId = $request->get('id');

            $this->turmaRepository->delete($turmaId);
            flash()->success('Turma excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A turma contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
