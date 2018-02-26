<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\DisciplinaRequest;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\NivelCursoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class DisciplinasController extends BaseController
{
    protected $disciplinaRepository;
    protected $nivelCursosRepository;

    public function __construct(DisciplinaRepository $disciplinaRepository, NivelCursoRepository $nivelCursoRepository)
    {
        $this->disciplinaRepository = $disciplinaRepository;
        $this->nivelCursosRepository = $nivelCursoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.disciplinas.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->disciplinaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'dis_id' => '#',
                'dis_nome' => 'Nome',
                'nvc_nome' => 'Nível',
                'dis_carga_horaria' => 'Carga Horária',
                'dis_creditos' => 'Créditos',
                'dis_action' => 'Ações'
            ))
                ->modifyCell('dis_action', function () {
                    return array('style' => 'width: 140px;');
                })

                ->means('dis_action', 'dis_id')
                ->means('dis_nvc_id', 'nivel')
                ->modify('dis_nvc_id', function ($nivel) {
                    return $nivel->nvc_nome;
                })
                ->modify('dis_action', function ($id) {
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
                                'route' => 'academico.disciplinas.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.disciplinas.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('dis_id', 'dis_nome', 'nvc_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::disciplinas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Academico::disciplinas.create', [
            'niveis' => $this->nivelCursosRepository->lists('nvc_id', 'nvc_nome')
        ]);
    }

    public function postCreate(DisciplinaRequest $request)
    {
        try {
            if (!$this->disciplinaRepository->validacao($request->all())) {
                $errors = array('dis_nome' => 'Disciplina já existe');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $disciplina = $this->disciplinaRepository->create($request->all());

            if (!$disciplina) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Disciplina criada com sucesso.');
            return redirect()->route('academico.disciplinas.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($disciplinaId)
    {
        $disciplina = $this->disciplinaRepository->find($disciplinaId);

        if (!$disciplina) {
            flash()->error('Disciplina não existe.');
            return redirect()->back();
        }

        return view('Academico::disciplinas.edit', [
            'disciplina' => $disciplina,
            'niveis' => $this->nivelCursosRepository->lists('nvc_id', 'nvc_nome')
        ]);
    }

    public function putEdit($id, DisciplinaRequest $request)
    {
        try {
            $disciplina = $this->disciplinaRepository->find($id);

            if (!$this->disciplinaRepository->validacao($request->all(), $id)) {
                $errors = array('dis_nome' => 'Disciplina já existe');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            if (!$disciplina) {
                flash()->error('Disciplina não existe.');
                return redirect()->route('academico.disciplinas.index');
            }

            $requestData = $request->only($this->disciplinaRepository->getFillableModelFields());

            if (!$this->disciplinaRepository->update($requestData, $disciplina->dis_id, 'dis_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Disciplina atualizada com sucesso.');
            return redirect()->route('academico.disciplinas.index');
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
            $disciplinaId = $request->get('id');

            $this->disciplinaRepository->delete($disciplinaId);

            flash()->success('Disciplina excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A disciplina contém dependências no sistema.');
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
