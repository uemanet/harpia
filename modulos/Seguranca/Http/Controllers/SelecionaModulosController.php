<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Auth;

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
        $user = $this->auth->user();

        $moduloRepository = new ModuloRepository();

        $modulos = $moduloRepository->getByUser($user->usr_id);

        $infoUser = array(
            'pes_nome' => $user->pessoa->pes_nome,
            'pes_telefone' => $user->pessoa->pes_telefone,
            'pes_email' => $user->pessoa->pes_email,
            'usr_usuario' => $user->usr_usuario
        );

        if ($modulos->count() == 1 && env('REDIRECT_MODULE')) {
            return redirect()->route(current($modulos)->mod_slug.'.index.index');
        }

        return view('Seguranca::selecionamodulos.index')->with(array('modulos'=>$modulos, 'infoUser'=>$infoUser));
    }
}
