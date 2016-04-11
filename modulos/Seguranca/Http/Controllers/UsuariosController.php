<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Security\UsuarioRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use Flash;
use Hash;
use Auth;

use App\Http\Requests\Security\StoreUsuarioRequest;

/**
 * Class UsuariosController.
 */
class UsuariosController extends Controller
{
	protected $usuarioRepository;

    public function __construct(UsuarioRepository $usuario)
    {
        $this->usuarioRepository = $usuario;
    }

    public function getIndex(){
    	$usuarios = $this->usuarioRepository->all();
        return view('security.usuarios.index',compact('usuarios'));
    }

    public function getCreate(){
    	return view('security.usuarios.create');
    }

    public function postCreate(StoreUsuarioRequest $request){
    	$dataRequest = $request->all();

    	$dataRequest['usr_senha'] = bcrypt($dataRequest['usr_senha']);
		$usuario = $this->usuarioRepository->create($dataRequest);

        if (!$usuario) {
            Flash::error('Erro ao tentar cadastrar usuário.');

            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Usuario criado com sucesso.');
        return redirect('security/usuarios');

    }

    public function postDelete(Request $request){
    	$id = $request->input('usr_id');

    	if($this->usuarioRepository->delete($id)) {
            Flash::success('Usuário excluído com sucesso.');
        } else {
            Flash::error('Erro ao tentar excluir a usuário');
        }

        return redirect()->back();
    }

     public function getEdit($id){
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario){
            Flash::error('Usuário não existe.');
            return redirect('/security/usuarios/index');
        }

        return view('security.usuarios.edit',compact(['usuario']));
    }

    public function putEdit($id, StoreUsuarioRequest $request){

        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            Flash::error('Usuário não existe.');
            return redirect('/security/usuarios/index');
        }

        $data = $request->only('usr_nome', 'usr_email', 'usr_telefone', 'usr_ativo');

        if($request->input('usr_senha') != ''){
            $data['usr_senha'] = bcrypt($request->input('usr_senha'));
        }

        $usuario = $this->usuarioRepository->update($data,$usuario->usr_id, 'usr_id');

        if (!$usuario) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('Usuário atualizado com sucesso.');
        return redirect('/security/usuarios/index');
    }

    public function getEditpassword($id){
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario){
            Flash::error('Usuário não existe.');
            return redirect('/security/usuarios/index');
        }

        return view('security.usuarios.editperfil',compact(['usuario']));
    }

    public function putEditpassword($id, StoreUsuarioRequest $request)
    {

        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            Flash::error('Usuário não existe.');
            return redirect('/security/usuarios/index');
        }


        if($request->input('usr_senha') != ''){
            $data['usr_senha'] = bcrypt($request->input('usr_senha'));
        }

        if($request->input('usr_senhaAtual') != ''){
            $data['usr_senhaAtual'] = bcrypt($request->input('usr_senhaAtual'));
        }

        if(Hash::check($request->input('usr_senhaAtual'), Auth::user()->usr_senha)){
            $data = $request->only('usr_email', 'usr_telefone', 'usr_senha');
            $data['usr_senha'] = bcrypt($request->input('usr_senha'));
            $usuario = $this->usuarioRepository->update($data,$usuario->usr_id, 'usr_id');
        }else{
            Flash::error('Erro ao alterar senha (Senhas atual está incorreta).');
            return redirect()->back()->withInput($request->all());
        }

        if (!$usuario) {
            Flash::error('Erro ao tentar salvar.');
            return redirect()->back()->withInput($request->all());
        }

            Flash::success('Usuário atualizado com sucesso.');
            return redirect('/security/usuarios/index');
    }
}
