<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\StorePermissaoRequest;
use App\Repositories\Security\RecursoRepository;
use App\Repositories\Security\PermissaoRepository;
use Illuminate\Http\Request;
use Validator;
use Flash;

class PermissoesController extends Controller
{
    protected $recursoRepository;
    protected $permissaoRepository;

    public function __construct(
        PermissaoRepository $permissao,
        RecursoRepository $recurso
    )
    {
        $this->recursoRepository = $recurso;
        $this->permissaoRepository = $permissao;
    }

    public function getIndex()
    {
        $permissoes = $this->permissaoRepository->all();
        return view('security.permissoes.index', compact('permissoes'));
    }

    public function getCreate()
    {
        $recursos = $this->recursoRepository->getItemSelect();
        return view('security.permissoes.create',compact(['recursos']));
    }

    public function postCreate(StorePermissaoRequest $request)
    {
        $permissao = $this->permissaoRepository->create($request->all());

        if (!$permissao) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Permissão criado com sucesso.');

        return redirect('security/permissoes');
    }

    public function getEdit($id){
        $permissao = $this->permissaoRepository->find($id);
        $recursos = $this->recursoRepository->getItemSelect();

        if (!$permissao){
            Flash::error('Permissão não existe.');
            return redirect('/security/permissoes/index');
        }

        return view('security.permissoes.edit',compact(['permissao','recursos']));
    }

    public function putEdit($id, StorePermissaoRequest $request){

        $permissao = $this->permissaoRepository->find($id);

        if (!$permissao) {
            Flash::error('Permissão não existe.');
            return redirect('/security/permissoes/index');
        }

        $data = $request->only('prm_rcs_id','prm_nome','prm_descricao');
        $permissao = $this->permissaoRepository->update($data,$request->input('prm_id'), 'prm_id');

        if (!$permissao) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Permissão atualizado com sucesso.');

        return redirect('security/permissoes');
    }

    public function postDelete(Request $request)
    {
        $id = $request->input('prm_id');

        if ($this->permissaoRepository->delete($id)) {
            Flash::success('Permissão excluído com sucesso.');
        } else {
            Flash::error('Erro ao tentar excluir o módulo.');
        }

        return redirect()->back();
    }
}
