<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\SetorRequest;
use Modulos\RH\Repositories\SetorRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Illuminate\Http\Request;

class SetoresController extends BaseController
{
    protected $setorRepository;

    public function __construct(SetorRepository $setorRepository)
    {
        $this->setorRepository = $setorRepository;
    }

    public function getIndex(Request $request)
    {

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.setores.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->setorRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'set_id' => '#',
                'set_descricao' => 'Descrição',
                'set_sigla' => 'Sigla',
                'set_action' => 'Ações'
            ))
                ->modifyCell('set_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('set_action', 'set_id')
                ->modify('set_action', function ($id) {
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
                                'route' => 'rh.setores.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.setores.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('set_id', 'set_descricao'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::setores.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::setores.create');
    }

    public function postCreate(SetorRequest $request)
    {
        try {
            $setor = $this->setorRepository->create($request->all());

            if (!$setor) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Setor criado com sucesso.');
            return redirect()->route('rh.setores.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($setorId)
    {
        $setor = $this->setorRepository->find($setorId);

        if (!$setor) {
            flash()->error('Setor não existe.');
            return redirect()->back();
        }

        return view('RH::setores.edit', compact('setor'));
    }

    public function putEdit($setorId, SetorRequest $request)
    {
        try {
            $setor = $this->setorRepository->find($setorId);

            if (!$setor) {
                flash()->error('Setor não existe.');
                return redirect()->route('rh.setores.index');
            }

            $requestData = $request->only($this->setorRepository->getFillableModelFields());

            if (!$this->setorRepository->update($requestData, $setor->set_id, 'set_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Setor atualizado com sucesso.');
            return redirect()->route('rh.setores.index');
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
            $setorId = $request->get('id');

            $this->setorRepository->delete($setorId);

            flash()->success('Setor excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O recurso contém dependências no sistema.');
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