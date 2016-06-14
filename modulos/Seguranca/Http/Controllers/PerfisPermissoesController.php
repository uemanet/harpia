<?php

namespace Modulos\Seguranca\Http\Controllers;

use Harpia\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;

use Modulos\Seguranca\Repositories\PerfilRepository;
use Modulos\Seguranca\Http\Requests\StoreModuloRequest;
use Illuminate\Http\Request;

class PerfisPermissoesController extends BaseController
{
    protected $perfilRepository;

    public function __construct(PerfilRepository $perfilRepository)
    {
        $this->perfilRepository = $perfilRepository;
    }

    public function getIndex(Request $request)
    {
        $tableData = $this->perfilRepository->paginateRequest($request->all());
        return view('Seguranca::perfispermissoes.index', ['tableData' => $tableData]);
    }

    public function getAtribuirpermissoes($perfilId)
    {
        $perfil = $this->perfilRepository->getPerfilWithModulo($perfilId);

        if(!sizeof($perfil)){
            return redirect('/seguranca/perfispermissoes/index');
        }
        
        $permissoes = $this->perfilRepository->getTreeOfPermissoesByPefilAndModulo($perfil->prf_id, $perfil->prf_mod_id);

        return view('Seguranca::perfispermissoes.atribuirpermissoes', compact('perfil', 'permissoes'));
    }

    public function postAtribuirpermissoes(Request $request)
    {
        $perfilId = $request->prf_id;

        if($request->input('permissoes') == ""){
            flash()->success('Não existem permissões cadastradas para o módulo no qual esse perfil faz parte.');
            return redirect('seguranca/perfispermissoes/atribuirpermissoes/'.$perfilId);
        }

        $permissoes = explode(',', $request->input('permissoes'));

        $this->perfilRepository->sincronizarPermissoes($perfilId, $permissoes);

        flash()->success('Permissões atribuidas com sucesso.');
        return redirect('seguranca/perfispermissoes/atribuirpermissoes/'.$perfilId);
    }
}