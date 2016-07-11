<?php

namespace Modulos\Seguranca\Http\Controllers;

use Harpia\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class UsuariosController extends BaseController
{
    protected $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/usuarios/create')->setIcon('fa fa-plus')->setStyle('btn btn-app bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->usuarioRepository->paginateRequest($request->all());

        return view('Seguranca::usuarios.index', ['tableData' => $tableData, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Seguranca::usuarios.create');
    }
}
