<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\StoreRecursoRequest;
use App\Repositories\Security\RecursoRepository;
use App\Repositories\Security\CategoriaRecursoRepository;
use App\Repositories\Security\ModuloRepository;
use Illuminate\Http\Request;
use Validator;
use Flash;

class RecursosController extends Controller
{
    protected $recursoRepository;
    protected $categoriaRepository;

    public function __construct(
        RecursoRepository $recurso,
        CategoriaRecursoRepository $categoria,
        ModuloRepository $modulo
    )
    {
        $this->recursoRepository = $recurso;
        $this->categoriaRepository = $categoria;
        $this->moduloRepository = $modulo;
    }

    public function getIndex()
    {
        $recursos = $this->recursoRepository->all();
        return view('security.recursos.index', compact('recursos'));
    }

    public function getCreate()
    {
        $categorias = $this->categoriaRepository->all()->lists('ctr_nome','ctr_id');
        $modulos    = $this->moduloRepository->all()->lists('mod_descricao','mod_id');

        return view('security.recursos.create',compact(['categorias','modulos']));
    }

    public function postCreate(StoreRecursoRequest $request)
    {
        $recurso = $this->recursoRepository->create($request->all());

        if (!$recurso) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Recurso criado com sucesso.');

        return redirect('security/recursos');
    }

    public function getEdit($id){
        $recurso = $this->recursoRepository->find($id);
        $categorias = $this->categoriaRepository->lists('ctr_nome','ctr_id');
        $modulos    = $this->moduloRepository->all()->lists('mod_descricao','mod_id');

        if (!$recurso){
            Flash::error('Recurso não existe.');
            return redirect('/security/recursos/index');
        }

        return view('security.recursos.edit',compact(['recurso','categorias','modulos']));
    }

    public function putEdit($id, StoreRecursoRequest $request){

        $recurso = $this->recursoRepository->find($id);

        if (!$recurso) {
            Flash::error('Recurso não existe.');
            return redirect('/security/recursos/index');
        }

        $data = $request->only('rcs_mod_id','rcs_ctr_id','rcs_nome','rcs_descricao','rcs_icone','rcs_ativo','rcs_ordem');
        $recurso = $this->recursoRepository->update($data,$request->input('rcs_id'), 'rcs_id');

        if (!$recurso) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Recurso atualizado com sucesso.');

        return redirect('security/recursos');
    }

    public function postDelete(Request $request)
    {
        $id = $request->input('rcs_id');

        if ($this->recursoRepository->delete($id)) {
            Flash::success('Recurso excluído com sucesso.');
        } else {
            Flash::error('Erro ao tentar excluir o módulo.');
        }

        return redirect()->back();
    }
}
