<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Http\Requests\Security\StoreCategoriaRecursoRequest;
use App\Repositories\Security\CategoriaRecursoRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;

class CategoriasRecursosController extends Controller
{
    protected $categoriaRecursoRepository;

    public function __construct(CategoriaRecursoRepository $categoriaRecursoRepository)
    {
        $this->categoriaRecursoRepository = $categoriaRecursoRepository;
    }

    public function getIndex()
    {
        $categoriasrecursos = $this->categoriaRecursoRepository->all();

        return view('security.categoriasrecursos.index', compact('categoriasrecursos'));
    }

    public function getCreate()
    {
        return view('security.categoriasrecursos.create');
    }

    public function postCreate(StoreCategoriaRecursoRequest $request)
    {
        $categoria = $this->categoriaRecursoRepository->create($request->all());

        if (!$categoria) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Categoria criada com sucesso.');

        return redirect('security/categoriasrecursos');
    }

    public function getEdit($id){
        $categoria = $this->categoriaRecursoRepository->find($id);

        if (!$categoria){
            Flash::error('Categoria não existe.');
            return redirect('/security/categoriasrecursos/index');
        }

        return view('security.categoriasrecursos.edit',compact(['categoria']));
    }

    public function putEdit($id, StoreCategoriaRecursoRequest $request){

        $categoria = $this->categoriaRecursoRepository->find($id);

        if (!$categoria) {
            Flash::error('Categoria não existe.');
            return redirect('/security/categoriasrecursos/index');
        }

        $data = $request->only('ctr_nome','ctr_icone','ctr_ordem','ctr_ativo');
        $categoria = $this->categoriaRecursoRepository->update($data,$request->input('ctr_id'), 'ctr_id');

        if (!$categoria) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Categoria atualizado com sucesso.');

        return redirect('security/categoriasrecursos');
    }

    public function postDelete(Request $request)
    {
        $id = $request->input('ctr_id');

        if($this->categoriaRecursoRepository->delete($id)) {
            Flash::success('Categoria excluída com sucesso.');
        } else {
            Flash::error('Erro ao tentar excluir a categoria');
        }

        return redirect()->back();
    }
}