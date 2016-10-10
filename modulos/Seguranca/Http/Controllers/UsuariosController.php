<?php

namespace Modulos\Seguranca\Http\Controllers;

use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class UsuariosController extends BaseController
{
    protected $usuarioRepository;
    protected $pessoaRepository;

    public function __construct(UsuarioRepository $usuarioRepository, PessoaRepository $pessoaRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/usuarios/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->usuarioRepository->paginateRequest($request->all());
        
        if($tableData->count()) {
            $tabela = $tableData->columns(array(
                'pes_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'doc_conteudo' => 'CPF',
                'pes_action' => 'Ações'
            ))
            ->modifyCell('pes_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('pes_action', 'pes_id')
            ->modify('pes_action', function ($id) {
                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-pencil',
                            'action' => '/seguranca/usuarios/edit/' . $id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'action' => '/seguranca/usuarios/delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('pes_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        

        return view('Seguranca::usuarios.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate($pesId = null)
    {
//        if(is_null($pesId)){
//            return view('Seguranca::usuarios.createfull');
//        }
//
//        $pessoa = $this->pessoaRepository->find($pesId);
//
//        if($pessoa){
//            return view('Seguranca::usuarios.create',['pessoa' => $pessoa]);
//        }

        return view('Seguranca::usuarios.create');
    }
}
