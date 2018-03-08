<?php

namespace Modulos\Integracao\Http\Controllers;

use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\TurmaRemovidaEvent;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Integracao\Models\AmbienteServico;
use Modulos\Integracao\Models\Servico;
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
use GuzzleHttp\Client;
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

    public function __construct(
        AmbienteVirtualRepository $ambienteVirtualRepository,
        ServicoRepository $servicoRepository,
        AmbienteServicoRepository $ambienteServicoRepository,
        CursoRepository $cursoRepository,
        AmbienteTurmaRepository $ambienteTurmaRepository,
        TurmaRepository $turmaRepository
    ) {
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
        $btnNovo->setName('Novo')->setRoute('integracao.ambientesvirtuais.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

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
                                'route' => 'integracao.ambientesvirtuais.adicionarturma',
                                'parameters' => ['id' => $id],
                                'label' => 'Turmas',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'text-blue',
                                'icon' => 'fa fa-server',
                                'route' => 'integracao.ambientesvirtuais.adicionarservico',
                                'parameters' => ['id' => $id],
                                'label' => 'Web Services',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'route' => 'integracao.ambientesvirtuais.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'integracao.ambientesvirtuais.delete',
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
            return redirect()->route('integracao.ambientesvirtuais.index');
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
                return redirect()->route('integracao.ambientesvirtuais.index');
            }

            $requestData = $request->only($this->ambienteVirtualRepository->getFillableModelFields());

            if (!$this->ambienteVirtualRepository->update($requestData, $ambientevirtual->amb_id, 'amb_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Ambiente Virtual atualizado com sucesso.');
            return redirect()->route('integracao.ambientesvirtuais.index');
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

        $ambServicos = new AmbienteServico();
        $idsAmbientesAdicionados = $ambServicos
            ->where("asr_amb_id", "=", $ambienteId)
            ->get()->pluck("asr_ser_id")->toArray();

        $servicoModel = new Servico();

        $servicos = $servicoModel->whereNotIn("ser_id", $idsAmbientesAdicionados)->pluck('ser_nome', 'ser_id');
        $servicosdoambiente = $ambiente->servicos;

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
        $servico = $this->servicoRepository->find($dados['asr_ser_id']);

        $validator = Validator::make($dados, [
            'asr_ser_id' => 'required|integer|min:1',
            'asr_amb_id' => 'required|integer|min:1',
            'asr_token' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $ambiente = $this->ambienteVirtualRepository->find($dados['asr_amb_id']);
            $url = $ambiente->amb_url . 'webservice/rest/server.php?wstoken=';
            $url .= $dados['asr_token'] . '&wsfunction=' . 'local_'. strtolower($servico->ser_nome) . '_ping&moodlewsrestformat=json';

            $client = new Client();
            $response = $client->request('POST', $url, ['query' => null]);

            $data = (array)json_decode($response->getBody());

            if (!array_key_exists('response', $data)) {
                // Erro ao verificar token
                return redirect()->back()->withErrors(['asr_token' => 'Token inválido'])->withInput();
            }

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
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() == 403) {
                flash()->error('Protocolos REST não estão habilitados no ambiente.');
                return redirect()->back();
            }

            if ($e->getCode() == 404) {
                flash()->error('URL incorreta!');
                return redirect()->back();
            }

            flash()->error('Erro de Conexão!');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atribuir serviço ao ambiente. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDeletarServico(Request $request)
    {
        try {
            $ambienteservicoId = $request->get('id');

            $this->ambienteServicoRepository->delete($ambienteservicoId);

            flash()->success('Serviço excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O serviço contém dependências no sistema.');
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
            $turma = $this->turmaRepository->find($dados['atr_trm_id']);

            if (!$turma->ambientes->count()) {
                $ambienteturma = $this->ambienteTurmaRepository->create($dados);

                if (!$ambienteturma) {
                    flash()->error('Erro ao tentar salvar.');
                    return redirect()->back()->withInput($request->all());
                }

                flash()->success('Turma vinculada com sucesso');

                # Evento de nova turma mapeada passando o objeto da turma
                $turma = $this->turmaRepository->find($dados['atr_trm_id']);

                if ($turma->trm_integrada) {
                    event(new TurmaMapeadaEvent($turma));
                }

                return redirect()->back();
            }

            flash()->error('Essa turma já está vinculada em um ambiente!');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar vincular turma ao ambiente. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
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

            $deletar = $this->turmaRepository->pendenciasTurma($turma->trm_id);

            if ($deletar) {
                flash()->error('Erro ao tentar deletar. A turma contém dependências no sistema.');
                return redirect()->back();
            }

            $this->ambienteTurmaRepository->delete($ambienteTurmaId);

            if ($turma->trm_integrada) {
                event(new TurmaRemovidaEvent($turma, $ambiente));
            }

            flash()->success('Turma excluída com sucesso.');

            DB::commit();
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            flash()->error('Erro ao tentar deletar. A turma contém dependências no sistema.');
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
