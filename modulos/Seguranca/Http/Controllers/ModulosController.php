<?php

namespace Modulos\Seguranca\Http\Controllers;

use App\Http\Controllers\Controller;

// use App\Http\Requests\Security\StoreModuloRequest;

use Modulos\Seguranca\Repositories\ModuloRepository;
use Illuminate\Http\Request;
use Validator;
use Flash;

class ModulosController extends Controller
{
    protected $moduloRepository;

    public function __construct(ModuloRepository $modulo)
    {
        $this->moduloRepository = $modulo;
    }

    public function getIndex()
    {
        $modulos = $this->moduloRepository->all();

        dd($modulos);

        return view('security.modulos.index', compact('modulos'));
    }

    // public function getCreate()
    // {
    //     return view('security.modulos.create');
    // }

    // public function postCreate(StoreModuloRequest $request)
    // {
    //     $modulo = $this->moduloRepository->create($request->all());

    //     if (!$modulo) {
    //         Flash::error('Erro ao tentar salvar.');

    //         return redirect()->back()->withInput($request->all());
    //     }

    //     Flash::success('Módulo criado com sucesso.');

    //     return redirect('security/modulos');
    // }

    // public function getEdit($id){
    //     $modulo = $this->moduloRepository->find($id);

    //     if (!$modulo){
    //         Flash::error('Módulo não existe.');
    //         return redirect('/security/modulos/index');
    //     }

    //     return view('security.modulos.edit',compact(['modulo']));
    // }

    // public function putEdit($id, StoreModuloRequest $request){

    //     $modulo = $this->moduloRepository->find($id);

    //     if (!$modulo) {
    //         Flash::error('Módulo não existe.');
    //         return redirect('/security/modulos/index');
    //     }

    //     $data = $request->only('mod_nome','mod_descricao','mod_icone','mod_ativo');
    //     $modulo = $this->moduloRepository->update($data,$request->input('mod_id'), 'mod_id');

    //     if (!$modulo) {
    //         Flash::error('Erro ao tentar salvar.');
    //         return redirect()->back()->withInput($request->all());
    //     }

    //     Flash::success('Módulo atualizado com sucesso.');

    //     return redirect('security/modulos');
    // }

    // public function postDelete(Request $request)
    // {
    //     $id = $request->input('mod_id');

    //     if($this->moduloRepository->delete($id)) {
    //         Flash::success('Módulo excluído com sucesso.');
    //     } else {
    //         Flash::error('Erro ao tentar excluir o modulo');
    //     }

    //     return redirect()->back();
    // }
}
