<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Repositories\Security\PerfilRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;

class PerfisPermissoesController extends Controller
{
    protected $perfilRepository;

    public function __construct(PerfilRepository $perfil)
    {
        $this->perfilRepository = $perfil;
    }

    public function getIndex()
    {
        $perfis = $this->perfilRepository->getAllPerfisWithModulos();
        return view('security.perfispermissoes.index', compact('perfis'));
    }

    public function getAtribuirpermissoes($perfilId)
    {
        $perfil = $this->perfilRepository->getPerfilWithModulo($perfilId);

        if(!sizeof($perfil)){
            return redirect('security/perfispermissoes/index');
        }

        $permissoes = $this->perfilRepository->getTreeOfPermissoesByPefilAndModulo($perfil->prf_id, $perfil->prf_mod_id);

        return view('security.perfispermissoes.atribuirpermissoes', compact('perfil', 'permissoes'));
    }

    public function postAtribuirpermissoes(Request $request)
    {
        $perfilId = $request->prf_id;

        if($request->input('permissoes') == ""){
            Flash::error('Não existem permissões cadastradas para o módulo no qual esse perfil faz parte.');
            return redirect('security/perfispermissoes/atribuirpermissoes/'.$perfilId);
        }

        $permissoes = explode(',', $request->input('permissoes'));

        $this->perfilRepository->sincronizarPermissoes($perfilId, $permissoes);

        Flash::success('Permissões atribuidas com sucesso.');
        return redirect('security/perfispermissoes/atribuirpermissoes/'.$perfilId);
    }
}
