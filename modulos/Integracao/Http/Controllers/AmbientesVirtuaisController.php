<?php

namespace Modulos\Integracao\Http\Controllers;

use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\DeleteOfertaTurmaEvent;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Http\Requests\AmbienteVirtualRequest;
use Modulos\Integracao\Http\Requests\ServicoRequest;
use Illuminate\Http\Request;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;
use Modulos\Integracao\Repositories\ServicoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Validator;
use DB;

class AmbientesVirtuaisController extends BaseController
{
    protected $ambienteVirtualRepository;
    protected $servicoRepository;
    protected $ambienteServicoRepository;
    protected $cursoRepository;
    protected $ambienteTurmaRepository;
    protected $turmaRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository,
                                ServicoRepository $servicoRepository,
                                AmbienteServicoRepository $ambienteServicoRepository,
                                CursoRepository $cursoRepository,
                                AmbienteTurmaRepository $ambienteTurmaRepository,
                                TurmaRepository $turmaRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->servicoRepository = $servicoRepository;
        $this->ambienteServicoRepository = $ambienteServicoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->ambienteTurmaRepository = $ambienteTurmaRepository;
        $this->turmaRepository = $turmaRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/integracao/ambientesvirtuais/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->ambienteVirtualRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'amb_id' => '#',
                'amb_nome' => 'Ambiente Virtual',
                'amb_url' => 'Url',
                'amb_versao' => 'Versão',
                'amb_action' => 'Ações'
            ))->modifyCell('amb_action', function () {
                return array('style' => 'width: 140px;');
            })->means('amb_action', 'amb_id')
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
            $ambientevirtual = $this->ambienteVirtualRepository->create($request->all());

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
        $ambientevirtual = $this->ambienteVirtualRepository->find($ambientevirtualId);

        if (!$ambientevirtual) {
            flash()->error('Ambiente Virtual não existe.');
            return redirect()->back();
        }

        return view('Integracao::ambientesvirtuais.edit', compact('ambientevirtual'));
    }

    public function putEdit($ambientevirtualId, AmbienteVirtualRequest $request)
    {
        try {
            $ambientevirtual = $this->ambienteVirtualRepository->find($ambientevirtualId);

            if (!$ambientevirtual) {
                flash()->error('Ambiente Virtual não existe.');
                return redirect('integracao/ambientesvirtuais/index');
            }

            $requestData = $request->only($this->ambienteVirtualRepository->getFillableModelFields());

            if (!$this->ambienteVirtualRepository->update($requestData, $ambientevirtual->amb_id, 'amb_id')) {
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

            $this->ambienteVirtualRepository->delete($ambientevirtualId);
            flash()->success('Ambiente Virtual excluído com sucesso.');

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            if ($e->getCode() == 23000) {
                flash()->error('Este ambiente ainda contém dependências no sistema e não pode ser excluído.');
                return redirect()->back();
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getAdicionarServico($ambienteId)
    {
        $ambiente = $this->ambienteVirtualRepository->find($ambienteId);

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
        $ambiente = $this->ambienteVirtualRepository->find($ambienteId);

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
            if (!$this->servicoRepository->verifyIfExistsAmbienteServico($dados['asr_amb_id'], $dados['asr_ser_id'])) {
                $ambienteservico = $this->ambienteServicoRepository->create($dados);

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

            if ($this->ambienteServicoRepository->delete($ambienteservicoId)) {
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
        $ambiente = $this->ambienteVirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Integracao::ambientesvirtuais.adicionarturma', compact('ambiente', 'cursos'));
    }

    public function postAdicionarTurma($ambienteId, Request $request)
    {
        $ambiente = $this->ambienteVirtualRepository->find($ambienteId);

        if (!$ambiente) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $validate['atr_trm_id'] = $request->atr_trm_id;
        $validate['atr_amb_id'] = $ambienteId;
        $validate['crs_id'] = $request->crs_id;
        $validate['ofc_id'] = $request->ofc_id;

        $validator = Validator::make($validate, [
            'atr_trm_id' => 'required|integer|min:1',
            'atr_amb_id' => 'required|integer|min:1',
            'crs_id' => 'required|integer|min:1',
            'ofc_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dados['atr_trm_id'] = $validate['atr_trm_id'];
        $dados['atr_amb_id'] = $validate['atr_amb_id'];

        try {
            if (!$this->ambienteVirtualRepository->verifyIfExistsAmbienteTurma($dados['atr_amb_id'], $dados['atr_trm_id'])) {
                $ambienteturma = $this->ambienteTurmaRepository->create($dados);

                if (!$ambienteturma) {
                    flash()->error('Erro ao tentar salvar.');
                    return redirect()->back()->withInput($request->all());
                }

                flash()->success('Turma vinculada com sucesso');

                # Evento de nova turma mapeada passando o objeto da turma
                $turma = $this->turmaRepository->find($dados['atr_trm_id']);

                if ($turma->trm_integrada) {
                    event(new TurmaMapeadaEvent($turma, "CREATE"));
                }

                return redirect()->back();
            }
            flash()->error('Essa turma já está vinculada em um ambiente!');
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

    public function postDeletarTurma(Request $request)
    {
        try {
            DB::beginTransaction();

            $ambienteTurmaId = $request->get('id');
            $ambienteTurma = $this->ambienteTurmaRepository->find($ambienteTurmaId);
            $turma = $this->turmaRepository->find($ambienteTurma->atr_trm_id);
            $ambiente = $turma->ambientes->first()->amb_id;

            $this->ambienteTurmaRepository->delete($ambienteTurmaId);

            if ($turma->trm_integrada) {
                event(new DeleteOfertaTurmaEvent($turma, "DELETE", $ambiente));
            }

            flash()->success('Turma excluída com sucesso.');

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar deletar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
