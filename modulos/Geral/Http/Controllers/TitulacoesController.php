<?php

namespace Modulos\Geral\Http\Controllers;

use Modulos\Geral\Http\Requests\TitulacaoRequest;
use Modulos\Geral\Repositories\TitulacaoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class TitulacoesController extends BaseController
{
    protected $titulacaoRepository;

    public function __construct(TitulacaoRepository $titulacaoRepository)
    {
        $this->titulacaoRepository = $titulacaoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/geral/titulacoes/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->titulacaoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'tit_id' => '#',
                'tit_nome' => 'Titulação',
                'tit_peso' => 'Peso',
                'tit_action' => 'Ações'
            ))
                ->modifyCell('tit_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('tit_action', 'tit_id')
                ->modify('tit_action', function ($id) {
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
                                'action' => '/geral/titulacoes/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => '/geral/titulacoes/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('tit_id', 'tit_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Geral::titulacoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Geral::titulacoes.create');
    }

    public function postCreate(TitulacaoRequest $request)
    {
        $titulacaoId = $request->input('tit_nome');

        try {
            if ($this->titulacaoRepository->verifyTitulacao($titulacaoId)) {
                $errors = array('tit_nome' => 'Nome da titulação já existe !');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $titulacao = $this->titulacaoRepository->create($request->all());

            if (!$titulacao) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Titulação criada com sucesso.');
            return redirect('/geral/titulacoes/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($titulacaoId)
    {
        $titulacao = $this->titulacaoRepository->find($titulacaoId);

        if (!$titulacao) {
            flash()->error('Titulação não existe.');
            return redirect()->back();
        }

        return view('Geral::titulacoes.edit', compact('titulacao'));
    }

    public function putEdit($titulacaoId, TitulacaoRequest $request)
    {
        try {
            $titulacao = $this->titulacaoRepository->find($titulacaoId);

            if (!$titulacao) {
                flash()->error('Titulação não existe.');
                return redirect('geral/titulacoes/index');
            }

            $requestData = $request->only($this->titulacaoRepository->getFillableModelFields());

            if (!$this->titulacaoRepository->update($requestData, $titulacao->tit_id, 'tit_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Titulação atualizada com sucesso.');
            return redirect('/geral/titulacoes/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $titulacaoId = $request->get('id');

            if ($this->titulacaoRepository->delete($titulacaoId)) {
                flash()->success('Titulação excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir a titulação');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
