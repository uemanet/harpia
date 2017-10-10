<?php

namespace Modulos\Integracao\Http\Controllers;

use Harpia\Event\SincronizacaoFactory;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;

class SincronizacaoController extends BaseController
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
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
            })->sortable(array('sym_table', 'sym_action'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Integracao::sincronizacao.index', [
            'tabela' => $tabela,
            'paginacao' => $paginacao
        ]);
    }

    public function show($id)
    {
        if ($this->sincronizacaoRepository->find($id)) {
            return view('Integracao::sincronizacao.show', [
                'sincronizacao' => $this->sincronizacaoRepository->find($id)
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

            return redirect()->route('integracao.sincronizacao.index');
        } catch (\Exception $e) {

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Não foi possível migrar esta sincronização.');
            return redirect()->route('integracao.sincronizacao.index');
        }
    }
}
