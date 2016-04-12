<?php

namespace App\Http\Controllers\Auth;

use App\Models\Geral\Usuario;
use Auth;
use Request;
use Validator;
use Flash;

/**
 * Class UsuarioController.
 */
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $usuario = Usuario::find(Auth::user()->int_usuario_id);

        return view('prof.profile.index', compact('usuario'));
    }

    public function postIndex()
    {
        $requestData = Request::all();

        $validator = Validator::make($requestData, Usuario::$rules);

        if ($validator->fails()) {
            return redirect('/profile')->withErrors($validator->errors()->all())->withInput();
        }

        $usuario = Usuario::find(Auth::user()->int_usuario_id);
        $usuario->fill($requestData);
        $usuario->save();

        Flash::success('Usuário alterado com sucesso.');

        return redirect('/profile');
    }

    public function postUpdatepassword()
    {
        $requestData = Request::all();

        $validator = Validator::make($requestData, [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/profile')->withErrors($validator)->withInput();
        }

        $usuario = Usuario::find(Auth::user()->int_usuario_id);
        $usuario->str_senha = bcrypt($requestData['password']);
        $usuario->save();

        Flash::success('Senha alterada com sucesso.');

        return redirect('/profile');
    }
}
