<?php

namespace Modulos\Integracao\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Http\Requests\AmbienteVirtualRequest;
use Modulos\Integracao\Http\Requests\ServicoRequest;
use Illuminate\Http\Request;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\ServicoRepository;

class AmbientesVirtuaisController extends BaseController
{
    protected $ambientevirtualRepository;
    protected $servicoRepository;

    public function __construct(AmbienteVirtualRepository $ambientevirtualRepository, ServicoRepository $servicoRepository)
    {
        $this->ambientevirtualRepository = $ambientevirtualRepository;
        $this->servicoRepository = $servicoRepository;

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

    public function getAdicionarServico($ambienteId)
    {
        $ambiente = $this->ambientevirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $servicos = $this->servicoRepository->lists('ser_id', 'ser_nome');

        $servicosdoambiente = $ambiente->servicos()->get();

        //dd($servicosdoambiente);
        return view('Integracao::ambientesvirtuais.adicionarservico', compact('ambiente', 'servicos', 'servicosdoambiente'));
    }

    public function postAdicionarServico($ambienteId, Request $request)
    {
        $ambiente = $this->ambientevirtualRepository->find($ambienteId);

        dd($ambienteId, $request);


        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $modulodisciplina['asr_ser_id'] = $dados['dis_id'];
        $modulodisciplina['asr_mdo_id'] = $dados['mod_id'];
        $modulodisciplina['asr_tipo_avaliacao'] = $dados['tipo_avaliacao'];

        $validator = Validator::make($request->all(), [
            'mod_id' => 'required',
            'prf_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            if (!$this->perfilRepository->verifyExistsPerfilModulo($request->input('mod_id'), $ambiente->usr_id)) {
                $ambiente->perfis()->attach($request->input('prf_id'));
                flash()->success('Perfil Atribuído com sucesso');
            } else {
                flash()->error('Ambiente já possui perfil associado ao módulo!');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar atribuir perfil. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
