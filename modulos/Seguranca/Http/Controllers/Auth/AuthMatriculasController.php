<?php

namespace Modulos\Seguranca\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Cache;
use Modulos\Seguranca\Events\LogoutOtherDevicesEvent;
use Modulos\Seguranca\Events\ReloadCacheEvent;
use Modulos\Seguranca\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Foundation\Application;
use Auth;

class AuthMatriculasController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */

    protected $auth;

    protected $app;

    protected function loggedOut(Request $request) {
        return redirect('/matriculas-alunos/login');
    }

    public function __construct(Guard $auth, Application $app)
    {

        $this->auth = $auth;
        $this->app = $app;
        $this->middleware('guest:matriculas-alunos')->except('logout');
    }


    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('Seguranca::auth.matriculas-alunos.login');
    }
//Auth::guard('admin')
    /**
     * Handle a login request to the application.
     *
     * @param $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(LoginRequest $request)
    {
        $credentials = $this->getCredentials($request);

        if (Auth::guard('matriculas-alunos')->attempt($credentials, $request->has('remember'))) {

            return redirect()->intended('/matriculas-alunos/index');
        }

        return redirect('/matriculas-alunos/index')
            ->withInput($request->only('usr_usuario', 'remember'))
            ->withErrors([
                'Usuário e/ou senha inválidos.',
            ]);
    }

    public function getLogout(Request $request)
    {

        Auth::logout();

        return redirect('/matriculas-alunos/login');
    }

    /** Get the needed authorization credentials from the request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return [
            'cpf' => $request->input('usr_usuario'),
            'password' => $request->input('usr_senha')
        ];
    }

    public function guard() {
        return Auth::guard('matriculas-alunos');
    }
}
