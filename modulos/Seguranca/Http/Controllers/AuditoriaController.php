<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Repositories\AuditoriaRepository;

class AuditoriaController extends BaseController
{
    protected $auditoriaRepository;

    public function __construct(AuditoriaRepository $auditoriaRepository)
    {
        $this->auditoriaRepository = $auditoriaRepository;
    }

    public function index(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->auditoriaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns([
                'log_id' => '#',
                'log_usr_id' => 'Usuário',
                'log_action' => 'Ação',
                'log_table' => 'Tabela Auditada',
                'log_table_id' => 'ID Registro',
                'created_at' => 'Data / Hora',
                'log_action_btn' => 'Detalhes'
            ])
                ->modifyCell('log_action_btn', function () {
                    return ['style' => 'width: 100px; text-align: center;'];
                })
                ->modify('log_usr_id', function ($log) {
                    if ($log->usuario && $log->usuario->pessoa) {
                        return $log->usuario->pessoa->pes_nome;
                    }
                    return $log->usuario ? $log->usuario->usr_usuario : 'Sistema / Desconhecido';
                })
                ->modify('log_action', function ($log) {
                    $badgeClass = [
                        'INSERT' => 'text-bg-success',
                        'UPDATE' => 'text-bg-warning',
                        'DELETE' => 'text-bg-danger'
                    ][$log->log_action] ?? 'text-bg-info';

                    return '<span class="badge rounded-pill '. $badgeClass .'">'.$log->log_action.'</span>';
                })
                ->modify('created_at', function ($log) {
                    return $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-';
                })
                ->modify('log_action_btn', function ($log) {
                    $jsonObj = htmlspecialchars($log->log_object, ENT_QUOTES, 'UTF-8');
                    return '<button type="button" class="btn btn-sm btn-info btn-ver-json" data-id="'. $log->log_id .'" data-json="'. $jsonObj .'" title="Ver Dados Auditados"><i class="fa fa-eye"></i></button>';
                })
                ->sortable(['log_id', 'log_table', 'created_at']);

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::auditoria.index', [
            'tabela' => $tabela,
            'paginacao' => $paginacao
        ]);
    }
}