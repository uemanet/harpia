<?php

namespace Modulos\Seguranca\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Http\Requests\RecursoRequest;
use Modulos\Seguranca\Http\Requests\StoreModuloRequest;
use Illuminate\Http\Request;
use Modulos\Seguranca\Repositories\CategoriaRecursoRepository;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Repositories\RecursoRepository;

class RecursosController extends BaseController
{
    protected $recursoRepository;
    protected $moduloRepository;
    protected $categoriaRecursoRepository;

    public function __construct(RecursoRepository $recursoRepository, ModuloRepository $moduloRepository, CategoriaRecursoRepository $categoriaRecursoRepository)
    {
        $this->recursoRepository = $recursoRepository;
        $this->moduloRepository = $moduloRepository;
        $this->categoriaRecursoRepository = $categoriaRecursoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/recursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->recursoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'rcs_id' => '#',
                'rcs_nome' => 'Recurso',
                'rcs_descricao' => 'Descrição',
                'rcs_action' => 'Ações'
            ))
                ->modifyCell('rcs_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('rcs_action', 'rcs_id')
                ->modify('rcs_action', function ($id) {
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
                                'action' => route('seguranca.recursos.getEdit', ['id' => $id]),
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => route('seguranca.recursos.delete'),
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('rcs_id', 'rcs_nome'));

            $paginacao = $tableData->appends($request->except('page'));
       }
        return view('Seguranca::recursos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');

        return view('Seguranca::recursos.create', compact('modulos'));
    }

    public function postCreate(RecursoRequest $request)
    {
        try {
            $recurso = $this->recursoRepository->create($request->all());

            if (!$recurso) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Recurso criado com sucesso.');

            return redirect(route('seguranca.recursos.index'));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($recursoId)
    {
        $recurso = $this->recursoRepository->find($recursoId);

        if (!$recurso) {
            flash()->error('Recurso não existe.');

            return redirect()->back();
        }

        $moduloId = $recurso->categoria->ctr_mod_id;

        $recurso->mod_id = $moduloId;

        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');
        $categorias = $this->categoriaRecursoRepository->listsAllByModulo($moduloId);


        return view('Seguranca::recursos.edit', compact('recurso', 'modulos', 'categorias'));
    }

    public function putEdit($id, RecursoRequest $request)
    {
        try {
            $recurso = $this->recursoRepository->find($id);

            if (!$recurso) {
                flash()->error('Recurso não existe.');

                return redirect(route('seguranca.recursos.index'));
            }

            $requestData = $request->only($this->recursoRepository->getFillableModelFields());

            if (!$this->recursoRepository->update($requestData, $recurso->rcs_id, 'rcs_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Recurso atualizado com sucesso.');

            return redirect(route('seguranca.recursos.index'));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();

        }
    }

    public function postDelete(Request $request)
    {
        try {
            $recursoId = $request->get('id');

            if ($this->recursoRepository->delete($recursoId)) {
                flash()->success('Recurso excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o recurso');
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
