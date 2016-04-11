<?php

namespace App\Modulos\Seguranca\Controllers;

use App\Repositories\Security\PerfilUsuarioRepository;
use App\Repositories\Security\ModuloRepository;
use App\Repositories\Security\UsuarioRepository;
use App\Repositories\Security\PerfilRepository;
use App\Http\Controllers\Controller;

use Flash;
use Request;

class PerfisUsuariosController extends Controller
{
    protected $perfilUsuarioRepository;
	protected $usuarioRepository;
	protected $perfilRepository;
	protected $moduloRepository;

    public function __construct(
    	PerfilUsuarioRepository $perfilUsuario,
    	UsuarioRepository $usuario,
    	PerfilRepository $perfil,
    	ModuloRepository $modulo)
    {
        $this->perfilUsuarioRepository = $perfilUsuario;
		$this->usuarioRepository = $usuario;
		$this->perfilRepository = $perfil;
		$this->moduloRepository = $modulo;
    }

    public function getIndex()
    {
        $usuarios = $this->usuarioRepository->all();
        return view('security.perfisusuarios.index', compact('usuarios'));
    }

    public function getAtribuirperfis($usrId)
    {
    	$usuario = $this->usuarioRepository->find($usrId);

        $perfis = $this->perfilRepository->getPerfilWithModuloByUsuarioId($usrId);

		$modulosVinculados = $this->perfilRepository->getModulosByUsuarioId($usrId);

        $params = array();
		$i = 0;

		foreach($modulosVinculados as $modulo){
			$params[$i] = $modulo->mod_id;
			$i++;
		}

		$resultado = $this->moduloRepository->getModulosNaoVinculados($params);

		$modulosNaoVinculados = array();
		$modulosNaoVinculados[0] = '';

		foreach($resultado as $modulo){
			$modulosNaoVinculados[$modulo->mod_id] = $modulo->mod_nome;
		}

		return view('security.perfisusuarios.atribuirperfis', compact('usuario', 'perfis', 'modulosNaoVinculados'));
    }

	public function getPerfis()
    {
    	$requestData = Request::all();

		$perfisNaoAtribuidos = $this->perfilRepository->getPerfisNaoAtribuidos($requestData['id']);

		$perfis = array();

		foreach($perfisNaoAtribuidos as $perfil){
			$perfis[$perfil->prf_id] = $perfil->prf_nome;
		}

		$json = json_encode($perfis);

        return $json;
    }

    public function postAtribuir()
    {
    	$usrId = (int) Request::input('usr_id');
        $perfilId = (int) Request::input('prf_id');

		$resultado = $this->perfilUsuarioRepository->atribuirPerfil($usrId, $perfilId);

		if($resultado){
        	Flash::success('Perfil atribuído com sucesso.');
        	return redirect('/security/perfisusuarios/atribuirperfis/'.$usrId);
        }else {
			Flash::success('Algo deu errado, tente novamente.');
        	return redirect('/security/perfisusuarios/atribuirperfis/'.$usrId);
		}
	}

	public function postDesvincularperfil()
    {
        $usrId = (int) Request::input('usr_id');
        $perfilId = (int) Request::input('prf_id');

        $resultado = $this->perfilUsuarioRepository->desvincularPerfil($usrId, $perfilId);

        if($resultado){
        	Flash::success('Perfil desvinculado com sucesso.');
        	return redirect('/security/perfisusuarios/atribuirperfis/'.$usrId);
        }else {
			Flash::success('Algo deu errado, tente novamente.');
        	return redirect('/security/perfisusuarios/atribuirperfis/'.$usrId);
		}
    }
}
