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
            ))
                ->modifyCell('ucr_action', function () {
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
                })
                ->sortable(array('pes_id', 'pes_nome'));
            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::vinculos.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function getVinculos($usuarioId, Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Adicionar vínculo')->setAction('/seguranca/usuarioscursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $data = $this->vinculoRepository->getCursos($usuarioId);
        $usuario = $this->usuarioRepository->find($usuarioId);

        $tabela = $data->columns(array(
            'crs_id' => '#',
            'crs_nome' => 'Pessoa',
            'crs_sigla' => 'Sigla',
            'ucr_action' => 'Ações',
        ))->modifyCell('ucr_action', function () {
            return ActionButton::grid([
              'type' => 'SELECT',
              'config' => [
                  'classButton' => 'btn-default',
                  'label' => 'Selecione',
              ],
              'buttons' => [
                  [
                      'classButton' => 'btn-delete text-red',
                      'icon' => 'fa fa-trash',
                      'action' => '/seguranca/modulos/delete',
                      'id' => $id,
                      'label' => 'Excluir',
                      'method' => 'post',
                  ],
              ],
          ]);
        })->sortable(array('crs_id', 'crs_nome'));

        $paginacao = $data->appends($request->except('page'));

        return view('Seguranca::vinculos.create', [
            'tabela' => $tabela,
            'paginacao' => $paginacao,
            'actionButtons' => $actionButtons,
            'usuario' => $usuario,
        ]);
    }
}
