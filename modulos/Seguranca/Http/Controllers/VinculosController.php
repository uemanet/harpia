<?php

namespace Modulos\Seguranca\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Modulos\Seguranca\Repositories\VinculoRepository;

class VinculosController extends BaseController
{
    protected $vinculoRepository;
    protected $usuarioRepository;

    public function __construct(VinculoRepository $vinculoRepository, UsuarioRepository $usuarioRepository)
    {
        $this->vinculoRepository = $vinculoRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;
        $tableData = null;

        if (!empty($request->all())) {
            $tableData = $this->usuarioRepository->paginateRequest($request->all());
        } else {
            return view('Seguranca::vinculos.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
        }

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'pes_id' => '#',
                'pes_nome' => 'Pessoa',
                'ucr_action' => 'Ações',
            ))->modifyCell('ucr_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('ucr_action', 'usr_id')
            ->modify('ucr_action', function ($id) {
                return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione',
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-link',
                                'action' => '/seguranca/usuarioscursos/vinculos/'.$id,
                                'label' => 'Vínculos',
                                'method' => 'get',
                            ],
                        ],
                  ]);
            })->sortable(array('pes_id', 'pes_nome'));
            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::vinculos.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    /**
    * Lista os vinculos do usuario
    * @param $usuarioId
    * @param $request
    */
    public function getVinculos($usuarioId, Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Adicionar vínculo')->setAction('/seguranca/usuarioscursos/create/'.$usuarioId)->setIcon('fa fa-link')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $data = $this->vinculoRepository->getCursosVinculados($usuarioId);
        $usuario = $this->usuarioRepository->find($usuarioId);

        $tabela = $data->columns(array(
            'crs_nome' => 'Curso',
            'crs_sigla' => 'Sigla',
            'crs_action' => 'Ações',
        ))
        ->means('crs_action', 'ucr_id')
        ->modify('crs_action', function ($id) {
            return ActionButton::grid([
                  'type' => 'SELECT',
                  'config' => [
                      'classButton' => 'btn-default',
                      'label' => 'Selecione',
                  ],
                 'buttons' => [
                    [
                      'classButton' => 'btn-delete text-red',
                      'icon' => 'fa fa-unlink',
                      'action' => '/seguranca/usuarioscursos/delete/',
                      'id' => $id,
                      'label' => 'Excluir vínculo',
                      'method' => 'post',
                    ],
                  ],
            ]);
        })
        ->sortable(array('crs_id', 'crs_nome'));

        $paginacao = $data->appends($request->except('page'));

        return view('Seguranca::vinculos.create', [
            'tabela' => $tabela,
            'paginacao' => $paginacao,
            'actionButtons' => $actionButtons,
            'usuario' => $usuario,
        ]);
    }

    public function getCreate($usuarioId)
    {
      $this->vinculoRepository->getCursosDisponiveis($usuarioId);
    }
}
