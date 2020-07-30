<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\RH\Http\Requests\BancoRequest;
use Modulos\RH\Repositories\BancoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class BancosController extends BaseController
{
    protected $bancoRepository;

    public function __construct(BancoRepository $bancoRepository)
    {
        $this->bancoRepository = $bancoRepository;
    }

    public function getIndex(Request $request)
    {

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.bancos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->bancoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'ban_id' => '#',
                'ban_nome' => 'Nome',
                'ban_codigo' => 'Código',
                'ban_sigla' => 'Sigla',
                'ban_action' => 'Ações'
            ))
                ->modifyCell('ban_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('ban_action', 'ban_id')
                ->modify('ban_action', function ($id) {
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
                                'route' => 'rh.bancos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.bancos.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('ban_id', 'ban_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::bancos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::bancos.create');
    }

    public function postCreate(BancoRequest $request)
    {
        try {
            $banco = $this->bancoRepository->create($request->all());

            if (!$banco) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Banco criada com sucesso.');
            return redirect()->route('rh.bancos.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($bancoId)
    {
        $banco = $this->bancoRepository->find($bancoId);

        if (!$banco) {
            flash()->error('Banco não existe.');
            return redirect()->back();
        }

        return view('RH::bancos.edit', compact('banco'));
    }

    public function putEdit($bancoId, BancoRequest $request)
    {
        try {
            $banco = $this->bancoRepository->find($bancoId);

            if (!$banco) {
                flash()->error('Banco não existe.');
                return redirect()->route('rh.bancos.index');
            }

            $requestData = $request->only($this->bancoRepository->getFillableModelFields());

            if (!$this->bancoRepository->update($requestData, $banco->ban_id, 'ban_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Banco atualizada com sucesso.');
            return redirect()->route('rh.bancos.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $bancoId = $request->get('id');

            $this->bancoRepository->delete($bancoId);

            flash()->success('Banco excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O recurso contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

}
