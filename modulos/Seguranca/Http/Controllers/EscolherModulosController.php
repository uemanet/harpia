<?php

namespace Modulos\Seguranca\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;

use Modulos\Seguranca\Providers\Security\Security;

/**
 * Class IndexController.
 */
class EscolherModulosController extends Controller
{
	protected $auth;
    protected $app;
	// protected $modulo;

	public function __construct(
		Guard $auth,
        Application $app
        // ModuloRepository $modulo
    ){
    	$this->auth = $auth;
        $this->app = $app;
        // $this->modulo = $modulo;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        echo '<pre>';
        var_dump('Selecione Modulo');

        $security = new Security($this->app);
        $security->makeCacheMenu();


        // $modulos = $this->modulo->getModulosUsuario($this->auth->getUser()->usr_id); 

        // if(sizeof($modulos) == 1 && env('REDIRECT_MODULE')){
        //     return redirect($modulos[0]->mod_nome.'/index');
        // }

        // return view('application.index')->with('modulos',$modulos);
    }
}