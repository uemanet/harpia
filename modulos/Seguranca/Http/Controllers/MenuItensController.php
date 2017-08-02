<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Events\ReloadCacheMenuEvent;
use Modulos\Seguranca\Http\Requests\MenuItemRequest;
use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Seguranca\Repositories\MenuItemRepository;
use Route;

class MenuItensController extends BaseController
{
    protected $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('seguranca.menuitens.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->menuItemRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mit_id' => '#',
                'mit_nome' => 'Nome',
                'mit_icone' => 'Ícone',
                'mit_rota' => 'Rota',
                'mit_mod_id' => 'Módulo',
                'mit_descricao' => 'Descrição',
                'mit_action' => 'Ações'
            ))
            ->modifyCell('pes_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->modify('mit_mod_id', function($obj) {
                return $obj->modulo->mod_nome;
            })
            ->means('mit_action', 'mit_id')
            ->modify('mit_action', function ($id) {
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
                            'route' => 'seguranca.menuitens.edit',
                            'parameters' => ['id' => $id],
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'route' => 'seguranca.menuitens.delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('mit_id', 'mit_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $modulos = Modulo::all()->pluck('mod_nome', 'mod_id');

        return view('Seguranca::menuitens.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'modulos' => $modulos]);
    }

    public function getCreate()
    {
        $modulos = Modulo::all()->pluck('mod_nome', 'mod_id');
        $itens = [];

        return view('Seguranca::menuitens.create', compact('modulos', 'itens'));
    }

    public function postCreate(MenuItemRequest $request)
    {
        if ($request->input('mit_rota') && !Route::has($request->input('mit_rota'))) {
            return redirect()->back()->withErrors(['mit_rota' => 'Rota Inválida'])->withInput();
        }

        try {
            $this->menuItemRepository->create($request->all());

            event(new ReloadCacheMenuEvent());

            flash()->success('Item de Menu criado com sucesso.');

            return redirect()->route('seguranca.menuitens.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function getEdit($id)
    {
        $itemMenu = $this->menuItemRepository->find($id);

        if (!$itemMenu) {
            flash()->error('Item de Menu não existe.');
            return redirect()->back();
        }

        $modulos = Modulo::all()->pluck('mod_nome', 'mod_id');
        $itens = MenuItem::where('mit_mod_id', $itemMenu->mit_mod_id)->pluck('mit_nome', 'mit_id');

        return view('Seguranca::menuitens.edit', compact('itemMenu', 'modulos', 'itens'));
    }

    public function putEdit($id, MenuItemRequest $request)
    {
        $itemMenu = $this->menuItemRepository->find($id);

        if (!$itemMenu) {
            flash()->error('Item de Menu não existe.');
            return redirect()->back();
        }

        if ($request->input('mit_rota') && !Route::has($request->input('mit_rota'))) {
            return redirect()->back()->withErrors(['mit_rota' => 'Rota Inválida'])->withInput();
        }

        try {
            $this->menuItemRepository->update($request->all(), $id);

            event(new ReloadCacheMenuEvent());

            flash()->success('Item de Menu atualizado com sucesso.');

            return redirect()->route('seguranca.menuitens.index');
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
            $id = $request->get('id');

            $this->menuItemRepository->delete($id);

            event(new ReloadCacheMenuEvent());

            flash()->success('Item de Menu excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O item contém dependências no sistema.');
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