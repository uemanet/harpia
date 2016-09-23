<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Repositories\CentroRepository;
use Modulos\Academico\Repositories\DepartamentoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\DepartamentoRequest;
use Illuminate\Http\Request;

class DepartamentosController extends BaseController
{
    protected $departamentoRepository;
    protected $professorRepository;
    protected $centroRepository;

    public function __construct(DepartamentoRepository $departamento,
                                ProfessorRepository $professor,
                                CentroRepository $centro)
    {
        $this->departamentoRepository = $departamento;
        $this->professorRepository = $professor;
        $this->centroRepository = $centro;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction(route('academico.departamentos.getCreate'))->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->departamentoRepository->paginateRequest($request->all());
        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'dep_id' => '#',
                'dep_nome' => 'Departamento',
                'dep_prf_diretor' => 'Diretor',
                'dep_action' => 'Ações'
            ))
                ->modifyCell('dep_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('dep_action', 'dep_id')
                ->means('dep_prf_diretor', 'diretor')
                ->modify('dep_prf_diretor', function ($diretor){
                    return $diretor->pessoa->pes_nome;
                })
                ->modify('dep_action', function ($id) {
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
                                'action' => route('academico.departamentos.getEdit', ['id' => $id]),
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => route('academico.departamentos.delete'),
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('dep_id', 'dep_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::departamentos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $centros = $this->centroRepository->lists('cen_id', 'cen_nome');

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome');

        return view('Academico::departamentos.create', ['centros' => $centros, 'professores' => $professores]);
    }

    public function postCreate(DepartamentoRequest $request)
    {
        try {
            $departamento = $this->departamentoRepository->create($request->all());

            if (!$departamento) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Departamento criado com sucesso.');

            return redirect(route('academico.departamentos.index'));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($departamentoId)
    {
        $departamento = $this->departamentoRepository->find($departamentoId);

        $centros = $this->centroRepository->lists('cen_id', 'cen_nome');

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome');


        if (!$departamento) {
            flash()->error('Departamento não existe.');

            return redirect()->back();
        }

        return view('Academico::departamentos.edit', ['departamento' => $departamento, 'centros' => $centros, 'professores' => $professores]);
    }

    public function putEdit($id, DepartamentoRequest $request)
    {
        try {
            $departamento = $this->departamentoRepository->find($id);

            if (!$departamento) {
                flash()->error('Departamento não existe.');

                return redirect(route('academico.departamentos.index'));
            }

            $requestData = $request->only($this->departamentoRepository->getFillableModelFields());

            if (!$this->departamentoRepository->update($requestData, $departamento->dep_id, 'dep_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Departamento atualizado com sucesso.');

            return redirect(route('academico.departamentos.index'));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $departamentoId = $request->get('id');

            if ($this->departamentoRepository->delete($departamentoId)) {
                flash()->success('Departamento excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o módulo');
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
