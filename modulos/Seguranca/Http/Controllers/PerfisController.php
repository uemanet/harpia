<?php

namespace Modulos\Seguranca\Http\Controllers;

use Harpia\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Http\Requests\PerfilRequest;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Repositories\PerfilRepository;
use Modulos\Seguranca\Http\Requests\StoreModuloRequest;
use Illuminate\Http\Request;

class PerfisController extends BaseController
{
    protected $perfilRepository;
    protected $moduloRepository;

    public function __construct(PerfilRepository $perfilRepository, ModuloRepository $moduloRepository)
    {
        $this->perfilRepository = $perfilRepository;
        $this->moduloRepository = $moduloRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/perfis/create')->setIcon('fa fa-plus')->setStyle('btn btn-app bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->perfilRepository->paginateRequest($request->all());

        return view('Seguranca::perfis.index', ['tableData' => $tableData, 'actionButton' => $actionButtons]);
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

            return redirect('/seguranca/perfis');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

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

                return redirect('/seguranca/perfis');
            }

            $requestData = $request->only($this->perfilRepository->getFillableModelFields());

            if (!$this->perfilRepository->update($requestData, $perfil->prf_id, 'prf_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Perfil atualizado com sucesso.');

            return redirect('/seguranca/perfis');
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
            $perfilId = $request->get('id');

            if ($this->perfilRepository->delete($perfilId)) {
                flash()->success('Perfil excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o perfil');
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

    public function getAtribuirpermissoes($perfilId)
    {
        $perfil = $this->perfilRepository->getPerfilWithModulo($perfilId);

        if (!sizeof($perfil)) {
            return redirect('/seguranca/perfis/index');
        }

        $permissoes = $this->perfilRepository->getTreeOfPermissoesByPefilAndModulo($perfil->prf_id, $perfil->prf_mod_id);

        return view('Seguranca::perfis.atribuirpermissoes', compact('perfil', 'permissoes'));
    }

    public function postAtribuirpermissoes(Request $request)
    {
        try {
            $perfilId = $request->prf_id;

            if ($request->input('permissoes') == "") {
                flash()->success('Não existem permissões cadastradas para o módulo no qual esse perfil faz parte.');

                return redirect('seguranca/perfis/atribuirpermissoes/'.$perfilId);
            }

            $permissoes = explode(',', $request->input('permissoes'));

            $this->perfilRepository->sincronizarPermissoes($perfilId, $permissoes);

            flash()->success('Permissões atribuídas com sucesso.');

            return redirect('seguranca/perfis/atribuirpermissoes/'.$perfilId);
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
