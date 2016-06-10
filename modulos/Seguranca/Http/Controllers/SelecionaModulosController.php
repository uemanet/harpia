<?php

namespace Modulos\Seguranca\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class SelecionaModulosController extends Controller
{
    protected $auth;
    protected $app;

    public function __construct(Guard $auth, Application $app)
    {
        $this->auth = $auth;
        $this->app = $app;

        $this->middleware(['auth']);
    }

    public function getIndex()
    {
        $modulos = $this->app[Seguranca::class]->getUserModules();

        $infoUser = array(
            'pes_nome' => $this->app['auth']->user()->pessoa->pes_nome,
            'pes_telefone' => $this->app['auth']->user()->pessoa->pes_telefone,
            'pes_email' => $this->app['auth']->user()->pessoa->pes_email,
            'usr_usuario' => $this->app['auth']->user()->usr_usuario
        );

        if(sizeof($modulos) == 1 && env('REDIRECT_MODULE')){
            return redirect(current($modulos)->mod_nome.'/index');
        }

        return view('Seguranca::selecionamodulos.index')->with(array('modulos'=>$modulos,'infoUser'=>$infoUser));
    }
}