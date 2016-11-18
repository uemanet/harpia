<?php

namespace Modulos\Integracao\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Http\Requests\AmbienteVirtualRequest;
use Modulos\Integracao\Http\Requests\ServicoRequest;
use Illuminate\Http\Request;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;
use Modulos\Integracao\Repositories\ServicoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Validator;

class AmbientesVirtuaisController extends BaseController
{
    protected $ambientevirtualRepository;
    protected $servicoRepository;
    protected $ambienteservicoRepository;
    protected $cursoRepository;

    public function __construct(AmbienteVirtualRepository $ambientevirtualRepository, ServicoRepository $servicoRepository, AmbienteServicoRepository $ambienteservicoRepository, CursoRepository $cursoRepository)
    {
        $this->ambientevirtualRepository = $ambientevirtualRepository;
        $this->servicoRepository = $servicoRepository;
        $this->ambienteservicoRepository = $ambienteservicoRepository;
        $this->cursoRepository = $cursoRepository;

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
                                'classButton' => '',
                                'icon' => 'fa fa-plus',
                                'action' => '/integracao/ambientesvirtuais/adicionarturma/'. $id,
                                'label' => 'Turmas',
                                'method' => 'get'
                            ],
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

        $servicos = $this->servicoRepository->listsServicosWithoutAmbiente($ambienteId);

        $servicosdoambiente = $ambiente->servicos()->get();

        return view('Integracao::ambientesvirtuais.adicionarservico', compact('ambiente', 'servicos', 'servicosdoambiente'));
    }

    public function postAdicionarServico($ambienteId, Request $request)
    {
        $ambiente = $this->ambientevirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $dados['asr_ser_id'] = $request->asr_ser_id;
        $dados['asr_amb_id'] = $ambienteId;
        $dados['asr_token'] = $request->asr_token;

        $validator = Validator::make($dados, [
            'asr_ser_id' => 'required',
            'asr_amb_id' => 'required',
            'asr_token' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            if (!$this->servicoRepository->verifyIfExistsAmbienteServico($dados['asr_amb_id'] , $dados['asr_ser_id'])) {

              $ambienteservico = $this->ambienteservicoRepository->create($dados);

              if (!$ambienteservico) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
              }
              flash()->success('Serviço Atribuído com sucesso');
              return redirect()->back();

            }
            flash()->error('Esse ambiente já possui este serviço!');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar atribuir serviço ao ambiente. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDeletarServico(Request $request)
    {
        try {
            $ambienteservicoId = $request->get('id');

            if ($this->ambienteservicoRepository->delete($ambienteservicoId)) {
                flash()->success('Serviço excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o serviço');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar deletar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getAdicionarTurma($ambienteId)
    {
        $ambiente = $this->ambientevirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        //$turmasdoambiente = $ambiente->turmas()->get();

        return view('Integracao::ambientesvirtuais.adicionarturma', compact('ambiente', 'cursos'));
    }

    public function postAdicionarTurma($ambienteId, Request $request)
    {
        $ambiente = $this->ambientevirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $validate['atr_trm_id'] = $request->atr_trm_id;
        $validate['atr_amb_id'] = $ambienteId;
        $validate['crs_id'] = $request->crs_id;
        $validate['ofc_id'] = $request->ofc_id;

        $validator = Validator::make($validate, [
            'atr_trm_id' => 'required',
            'atr_amb_id' => 'required',
            'crs_id' => 'required',
            'ofc_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dados['atr_trm_id'] = $validate['atr_trm_id'];
        $dados['atr_amb_id'] = $validate['atr_amb_id'];
        try {

            if (!$this->ambientevirtualRepository->verifyIfExistsAmbienteTurma($dados['atr_amb_id'] , $dados['atr_trm_id'])) {

              $ambienteturma = $this->ambienteturmaRepository->create($dados);

              if (!$ambienteturma) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
              }
              flash()->success('Turma vinculada com sucesso');
              return redirect()->back();

            }
            flash()->error('Esse ambiente já possui esta turma!');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar vincular turma ao ambiente. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
