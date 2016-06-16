<?php

namespace Modulos\Geral\Http\Controllers;

use Harpia\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\PessoaRepository;
use Illuminate\Http\Request;

class PessoasController extends BaseController
{
    protected $pessoaRepository;

    public function __construct(PessoaRepository $pessoa)
    {
        $this->pessoaRepository = $pessoa;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/geral/pessoas/create')->setIcon('fa fa-plus')->setStyle('btn btn-app bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->pessoaRepository->paginateRequest($request->all());

        return view('Geral::pessoas.index', ['tableData' => $tableData, 'actionButton' => $actionButtons]);
    }
}
