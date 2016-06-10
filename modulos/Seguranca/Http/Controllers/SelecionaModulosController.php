<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class SelecionaModulosController extends BaseController
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
            'pes_nome' => $this->auth->user()->pessoa->pes_nome,
            'pes_telefone' => $this->auth->user()->pessoa->pes_telefone,
            'pes_email' => $this->auth->user()->pessoa->pes_email,
            'usr_usuario' => $this->auth->user()->usr_usuario
        );

        if(sizeof($modulos) == 1 && env('REDIRECT_MODULE')){
            return redirect(current($modulos)->mod_nome.'/index');
        }

        return view('Seguranca::selecionamodulos.index')->with(array('modulos'=>$modulos,'infoUser'=>$infoUser));
    }
}