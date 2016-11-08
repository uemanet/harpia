<?php

namespace Modulos\Integracao\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Http\Requests\AmbienteVirtualRequest;
use Illuminate\Http\Request;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class AmbientesVirtuaisController extends BaseController
{
    protected $ambientevirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambientevirtualRepository)
    {
        $this->ambientevirtualRepository = $ambientevirtualRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/integracao/ambientesvirtuais/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->ambientevirtualRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'amb_id' => '#',
                'amb_nome' => 'Ambiente Virtual',
                'amb_url' => 'Url',
                'amb_versao' => 'Versão',
                'amb_action' => 'Ações'
            ))
                ->modifyCell('amb_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('amb_action', 'amb_id')
                ->modify('amb_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => 'text-blue',
                                'icon' => 'fa fa-server',
                                'action' => '/integracao/ambientesvirtuais/adicionarservico/'. $id,
                                'label' => 'Web Services',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'action' => '/integracao/ambientesvirtuais/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => '/integracao/ambientesvirtuais/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('amb_id', 'amb_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Integracao::ambientesvirtuais.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Integracao::ambientesvirtuais.create');
    }

    public function postCreate(AmbienteVirtualRequest $request)
    {
        try {
            $ambientevirtual = $this->ambientevirtualRepository->create($request->all());

            if (!$ambientevirtual) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Ambiente Virtual criado com sucesso.');
            return redirect('/integracao/ambientesvirtuais/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($ambientevirtualId)
    {
        $ambientevirtual = $this->ambientevirtualRepository->find($ambientevirtualId);

        if (!$ambientevirtual) {
            flash()->error('Ambiente Virtual não existe.');
            return redirect()->back();
        }

        return view('Integracao::ambientesvirtuais.edit', compact('ambientevirtual'));
    }

    public function putEdit($ambientevirtualId, AmbienteVirtualRequest $request)
    {
        try {
            $ambientevirtual = $this->ambientevirtualRepository->find($ambientevirtualId);

            if (!$ambientevirtual) {
                flash()->error('Ambiente Virtual não existe.');
                return redirect('integracao/ambientesvirtuais/index');
            }

            $requestData = $request->only($this->ambientevirtualRepository->getFillableModelFields());

            if (!$this->ambientevirtualRepository->update($requestData, $ambientevirtual->amb_id, 'amb_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Ambiente Virtual atualizado com sucesso.');
            return redirect('/integracao/ambientesvirtuais/index');
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
            $ambientevirtualId = $request->get('id');

            if ($this->ambientevirtualRepository->delete($ambientevirtualId)) {
                flash()->success('Ambiente Virtual excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o ambiente virtual');
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

    public function getAtribuirservico($ambienteId)
    {
        $ambiente = $this->ambientevirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $modulos = $this->perfilRepository->getModulosWithoutPerfis($usuario->usr_id);

        return view('Seguranca::usuarios.atribuirperfil', compact('usuario', 'modulos'));
    }
}
