<?php

namespace Modulos\Seguranca\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Http\Requests\ModuloRequest;
use Illuminate\Http\Request;

class ModulosController extends BaseController
{
    protected $moduloRepository;

    public function __construct(ModuloRepository $modulo)
    {
        $this->moduloRepository = $modulo;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/modulos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->moduloRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                    'mod_id' => '#',
                    'mod_nome' => 'Módulo',
                    'mod_descricao' => 'Descrição',
                    'mod_action' => 'Ações'
                ))
                    ->modifyCell('mod_action', function () {
                        return array('style' => 'width: 140px;');
                    })
                    ->means('mod_action', 'mod_id')
                    ->modify('mod_action', function ($id) {
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
                                    'action' => '/seguranca/modulos/edit/' . $id,
                                    'label' => 'Editar',
                                    'method' => 'get'
                                ],
                                [
                                    'classButton' => 'btn-delete text-red',
                                    'icon' => 'fa fa-trash',
                                    'action' =>  '/seguranca/modulos/delete',
                                    'id' => $id,
                                    'label' => 'Excluir',
                                    'method' => 'post'
                                ]
                            ]
                        ]);
                    })
                    ->sortable(array('mod_id', 'mod_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::modulos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Seguranca::modulos.create');
    }

    public function postCreate(ModuloRequest $request)
    {
        try {
            $modulo = $this->moduloRepository->create($request->all());

            if (!$modulo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Módulo criado com sucesso.');

            return redirect('/seguranca/modulos/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($moduloId)
    {
        $modulo = $this->moduloRepository->find($moduloId);

        if (!$modulo) {
            flash()->error('Módulo não existe.');

            return redirect()->back();
        }

        return view('Seguranca::modulos.edit', compact('modulo'));
    }

    public function putEdit($id, ModuloRequest $request)
    {
        try {
            $modulo = $this->moduloRepository->find($id);

            if (!$modulo) {
                flash()->error('Módulo não existe.');

                return redirect('/seguranca/modulos/index');
            }

            $requestData = $request->only($this->moduloRepository->getFillableModelFields());

            if (!$this->moduloRepository->update($requestData, $modulo->mod_id, 'mod_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Módulo atualizado com sucesso.');

            return redirect('/seguranca/modulos/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $moduloId = $request->get('id');

            if ($this->moduloRepository->delete($moduloId)) {
                flash()->success('Módulo excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o módulo');
            }

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O módulo contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }
}
