<?php

namespace Modulos\Integracao\Http\Controllers;

use GuzzleHttp\Client;
use Harpia\Event\SincronizacaoFactory;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;

class SincronizacaoController extends BaseController
{
    protected $sincronizacaoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository, AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function index(Request $request)
    {
        $tabela = null;
        $paginacao = null;


        $tableData = $this->sincronizacaoRepository->paginateRequest($request->all());
        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'sym_id' => '#',
                'sym_table' => 'Tabela',
                'sym_action' => 'Ação',
                'sym_status' => 'Status',
                'sym_mensagem' => 'Mensagem',
                'sym_data_envio' => 'Data de Envio',
                'sym_version' => 'Versão',
                'sym_actions' => 'Ações'
            ))->modifyCell('sym_actions', function () {
                return array('style' => 'width: 140px;');
            })->modify('sym_status', function ($sync) {
                if ($sync->sym_status == 1) {
                    return "<small class=\"label bg-blue\">Pendente</small>";
                }

                if ($sync->sym_status == 2) {
                    return "<small class=\"label bg-green\">Sucesso</small>";
                }

                if ($sync->sym_status == 3) {
                    return "<small class=\"label bg-red\">Falha</small>";
                }
            })->modify('sym_data_envio', function ($sync) {
                if ($sync->sym_data_envio) {
                    return date('d/m/Y', strtotime($sync->sym_data_envio));
                }
            })->modify('sym_actions', function ($sync) {
                $buttons = [
                    [
                        'classButton' => '',
                        'icon' => 'fa fa-eye',
                        'route' => 'integracao.sincronizacao.show',
                        'parameters' => ['id' => $sync->sym_id],
                        'label' => 'Visualizar',
                        'method' => 'get'
                    ],
                ];

                if ($sync->sym_status == 1 || $sync->sym_status == 3) {
                    $buttons[] = [
                        'classButton' => 'btn-migrar',
                        'icon' => 'fa fa-refresh',
                        'route' => 'integracao.sincronizacao.sincronizar',
                        'parameters' => ['id' => $sync->sym_id],
                        'label' => ' Migrar',
                        'id' => $sync->sym_id,
                        'method' => 'post'
                    ];
                }

                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => $buttons
                ]);
            })->sortable(array('sym_id', 'sym_table', 'sym_action', 'sym_data_envio'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Integracao::sincronizacao.index', [
            'tabela' => $tabela,
            'paginacao' => $paginacao
        ]);
    }

    public function show($id)
    {
        $sincronizacao = $this->sincronizacaoRepository->find($id);

        if ($sincronizacao) {

            if ($sincronizacao->sym_status == 3 and (
                    $sincronizacao->sym_table === 'acd_ofertas_disciplinas' or
                    $sincronizacao->sym_table === 'acd_matriculas' or
                    $sincronizacao->sym_table === 'acd_tutores_grupos'
                )
            ) {
                $syncData = $this->sincronizacaoRepository->getSyncData($sincronizacao);

                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($syncData['turma']->trm_id);
                $url = $ambiente->amb_url . 'webservice/rest/server.php?wstoken=';
                $url .= $ambiente->integracao()->asr_token . '&wsfunction=local_integracao_get_user&moodlewsrestformat=json';

                $client = new Client();

                $response = $client->request('POST', $url, ['form_params' => $syncData['user']]);

                $data = (array)json_decode($response->getBody());

            }

            return view('Integracao::sincronizacao.show', [
                'sincronizacao' => $this->sincronizacaoRepository->find($id),
                'pessoa' => $syncData['pessoa'] ?? null,
                'user' => $data['data'] ?? null
            ]);
        }

        flash()->error('Registro não encontrado.');
        return redirect()->back();
    }

    public function postSincronizar($id, Request $request)
    {
        $sincronizacao = $this->sincronizacaoRepository->find($id);

        if (!$sincronizacao) {
            flash()->error('Registro não encontrado.');
            return redirect()->route('integracao.sincronizacao.index');
        }

        if ($sincronizacao->sym_status == 2) {
            flash()->error('Sincronização já realizada com sucesso anteriormente.');
            return redirect()->route('integracao.sincronizacao.index');
        }

        try {
            $event = SincronizacaoFactory::factory($sincronizacao);
            event($event); // Dispara event

            flash()->success('Sincronização realizada com sucesso!');
            return redirect()->route('integracao.sincronizacao.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Não foi possível migrar esta sincronização.');
            return redirect()->route('integracao.sincronizacao.index');
        }
    }

    public function postMapear($id, Request $request)
    {
        $sincronizacao = $this->sincronizacaoRepository->find($id);

        if ($sincronizacao) {

            $syncData = $this->sincronizacaoRepository->getSyncData($sincronizacao);

            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($syncData['turma']->trm_id);
            $url = $ambiente->amb_url . 'webservice/rest/server.php?wstoken=';
            $url .= $ambiente->integracao()->asr_token . '&wsfunction=local_integracao_map_user&moodlewsrestformat=json';

            $client = new Client();

            $response = $client->request('POST', $url, ['form_params' => $syncData['user']]);

            $data = (array)json_decode($response->getBody());

            flash()->success('Usuário mapeado com sucesso');
            return redirect()->back();

            return view('Integracao::sincronizacao.show', [
                'sincronizacao' => $this->sincronizacaoRepository->find($id),
                'pessoa' => $syncData['pessoa'],
                'user' => $data['data']
            ]);
        }

        flash()->error('Registro não encontrado.');
        return redirect()->back();
    }
}
