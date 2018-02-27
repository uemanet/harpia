<?php

namespace Modulos\Seguranca\Http\Controllers;

use Modulos\Seguranca\Events\ReloadCacheEvent;
use Modulos\Seguranca\Http\Requests\PerfilRequest;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Repositories\PerfilRepository;

class PerfisController extends BaseController
{
    protected $perfilRepository;
    protected $moduloRepository;

    public function __construct(PerfilRepository $perfilRepository)
    {
        $this->perfilRepository = $perfilRepository;
        $this->moduloRepository = new ModuloRepository();
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('seguranca.perfis.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->perfilRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'prf_id' => '#',
                'prf_nome' => 'Perfil',
                'prf_descricao' => 'Descrição',
                'prf_mod_id' => 'Módulo',
                'prf_action' => 'Ações'
            ))
            ->modify('prf_mod_id', function ($obj) {
                return $obj->modulo->mod_nome;
            })
            ->modifyCell('prf_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('prf_action', 'prf_id')
            ->modify('prf_action', function ($id) {
                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
                        [
                            'classButton' => 'text-blue',
                            'icon' => 'fa fa-check-square-o',
                            'route' => 'seguranca.perfis.atribuirpermissoes',
                            'parameters' => ['id' => $id],
                            'label' => 'Permissões',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-pencil',
                            'route' => 'seguranca.perfis.edit',
                            'parameters' => ['id' => $id],
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'route' =>  'seguranca.perfis.delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('prf_id', 'prf_nome'));
            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::perfis.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');

        return view('Seguranca::perfis.create', compact('modulos'));
    }

    public function postCreate(PerfilRequest $request)
    {
        try {
            $perfil = $this->perfilRepository->create($request->all());

            if (!$perfil) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Perfil criado com sucesso.');

            return redirect()->route('seguranca.perfis.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($perfilId)
    {
        $perfil = $this->perfilRepository->find($perfilId);

        if (!$perfil) {
            flash()->error('Perfil não existe.');

            return redirect()->back();
        }

        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');

        return view('Seguranca::perfis.edit', compact('perfil', 'modulos'));
    }

    public function putEdit($id, PerfilRequest $request)
    {
        try {
            $perfil = $this->perfilRepository->find($id);

            if (!$perfil) {
                flash()->error('Perfil não existe.');

                return redirect()->route('seguranca.perfis.index');
            }

            $requestData = $request->only('prf_nome', 'prf_descricao');

            if (!$this->perfilRepository->update($requestData, $perfil->prf_id, 'prf_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Perfil atualizado com sucesso.');

            return redirect()->route('seguranca.perfis.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $perfilId = $request->get('id');

            $this->perfilRepository->delete($perfilId);

            flash()->success('Perfil excluído com sucesso.');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O perfil contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }


    public function getAtribuirpermissoes($perfilId)
    {
        $perfil = $this->perfilRepository->find($perfilId);

        if (!sizeof($perfil)) {
            flash()->error('Perfil não existe.');
            return redirect()->route('seguranca.perfis.index');
        }

        $permissoes = $this->perfilRepository->getPerfilModulo($perfil);

        return view('Seguranca::perfis.atribuirpermissoes', compact('perfil', 'permissoes'));
    }

    public function postAtribuirpermissoes($id, Request $request)
    {
        try {
            $perfilId = $request->prf_id;

            if ($request->input('permissao') == "") {
                $permissoes = [];
                $this->perfilRepository->sincronizarPermissoes($perfilId, $permissoes);

                event(new ReloadCacheEvent());
                flash()->success('Permissões atribuídas com sucesso.');

                return redirect('seguranca/perfis/atribuirpermissoes/'.$perfilId);
            }

            $permissoes = explode(',', $request->input('permissao'));

            $this->perfilRepository->sincronizarPermissoes($perfilId, $permissoes);

            event(new ReloadCacheEvent());
            flash()->success('Permissões atribuídas com sucesso.');

            return redirect('seguranca/perfis/atribuirpermissoes/'.$perfilId);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }
}
