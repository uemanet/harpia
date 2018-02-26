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

class AuthController extends Controller
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
//    protected $redirectTo = '/';

    protected $auth;

    protected $app;

    /**
     * Create a new authentication controller instance.
     */
    public function __construct(Guard $auth, Application $app)
    {
        $this->auth = $auth;
        $this->app = $app;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('Seguranca::auth.login');
    }

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

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            event(new LogoutOtherDevicesEvent($request));
            event(new ReloadCacheEvent());

            return redirect()->intended('/');
        }

        return redirect('/login')
            ->withInput($request->only('usr_usuario', 'remember'))
            ->withErrors([
                'Usuário e/ou senha inválidos.',
            ]);
    }

    public function getLogout(Request $request)
    {
        $usrId = $this->app['auth']->user()->usr_id;

        Cache::forget('MENU_'.$usrId);
        Cache::forget('PERMISSOES_'.$usrId);

        $this->auth->logout();

        $request->session()->flush();

        return redirect('/login');
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
            'usr_usuario' => $request->input('usr_usuario'),
            'password' => $request->input('usr_senha'),
            'usr_ativo' => 1,
        ];
    }
}
