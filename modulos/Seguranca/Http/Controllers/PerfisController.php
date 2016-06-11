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

    public function __construct(PerfilRepository $perfil, ModuloRepository $moduloRepository)
    {
        $this->perfilRepository = $perfil;
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

    public function getEdit($idPerfil)
    {
        $perfil = $this->perfilRepository->find($idPerfil);

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

    public function deleteDelete(Request $request)
    {
        try {
            $perfilId = $request->input('prf_id');

            if($this->perfilRepository->delete($perfilId)) {
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
}
