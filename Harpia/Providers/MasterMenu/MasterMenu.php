<?php

namespace Harpia\Providers\MasterMenu;

// use Modulos\Seguranca\Providers\Security\Security;

use DB;

class MasterMenu{
   	protected $request;
    protected $auth;

   	public function __construct($app)
   	{
    	$this->request = $app['request'];
      $this->auth = $app['auth'];
   	}

    public function make(){
        
    }

    public function render()
    {
      $path = preg_split('/\//', $this->request->path());
      $modulo = current($path);
      $controller = next($path);

      //$userId = $this->auth->user()->getAuthIdentifier();
      //$permissoes = $this->getPermissoes($modulo, $userId);
      //return 'bruno';
    }

}