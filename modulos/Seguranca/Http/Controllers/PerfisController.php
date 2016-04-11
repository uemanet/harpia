<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Http\Requests\Security\StorePerfilRequest;
use App\Repositories\Security\PerfilRepository;
use App\Repositories\Security\ModuloRepository;
use App\Models\Security\Perfil;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;

class PerfisController extends Controller
{
    protected $perfilRepository;
	protected $moduloRepository;

    public function __construct(PerfilRepository $perfilRepository, ModuloRepository $moduloRepository)
    {
        $this->perfilRepository = $perfilRepository;
		$this->moduloRepository = $moduloRepository;
    }

    public function getIndex()
    {
        $perfis = $this->perfilRepository->all();
        return view('security.perfis.index', compact('perfis'));
    }

    public function getCreate()
    {
    	$modulos = $this->moduloRepository->lists('mod_nome', 'mod_id');
        return view('security.perfis.create', compact('modulos'));
    }

    public function postCreate(StorePerfilRequest $request)
    {
        $perfil = $this->perfilRepository->create($request->all());

        if (!$perfil) {
            Flash::error('Erro ao tentar salvar.');

            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Perfil criado com sucesso.');
        return redirect('security/perfis');
    }

    public function postDelete(Request $request)
    {
        $id = $request->input('prf_id');

        if($this->perfilRepository->delete($id)) {
            Flash::success('Perfil excluído com sucesso.');
        } else {
            Flash::error('Erro ao tentar excluir a perfil');
        }

        return redirect()->back();
    }

    public function getEdit($id){
        $perfil = $this->perfilRepository->find($id);
        $modulos = $this->moduloRepository->lists('mod_nome', 'mod_id');

        if (!$perfil){
            Flash::error('Perfil não existe.');
            return redirect('/security/perfis/index');
        }

        return view('security.perfis.edit',compact(['perfil','modulos']));
    }

    public function putEdit($id, StorePerfilRequest $request){

        $perfil = $this->perfilRepository->find($id);

        if (!$perfil) {
            Flash::error('Perfil não existe.');
            return redirect('/security/perfis/index');
        }

        $data = $request->only('prf_mod_id', 'prf_nome', 'prf_descricao');

        $perfil = $this->perfilRepository->update($data,$perfil->prf_id, 'prf_id');

        if (!$perfil) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Perfil atualizado com sucesso.');
            return redirect('/security/perfis/index');
    }

}
