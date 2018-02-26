<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\Event;

/**
 * Evento para atualizacao de exclusao de registros associados a ambientes virtuais
 *
 * @package Modulos\Integracao\Events
 */
class DeleteSincronizacaoEvent extends Event
{
    private $status;
    private $sendingDate;
    private $message;
    private $extraInformation;

    public function __construct($table,
                                $tableId,
                                $status = 2,
                                $message = null,
                                $action = "DELETE",
                                $sendingDate = null,
                                $extraInformation = null)
    {
        $date = new \DateTime('NOW');

        $this->table = $table;
        $this->tableId = $tableId;
        $this->action = $action;
        $this->status = $status;
        $this->message = $message;
        $this->extraInformation = $extraInformation;
        $this->sendingDate = is_null($sendingDate) ? $date->format('Y-m-d H:i:s') : $sendingDate;
    }

    /**
     * Dados para atualizacao na tabela de sincronizacao
     * @return array
     */
    public function getData()
    {
        return [
            'sym_table' => $this->table,
            'sym_table_id' => $this->tableId,
            'sym_action' => $this->action,
            'sym_status' => $this->status,
            'sym_mensagem' => $this->message,
            'sym_data_envio' => $this->sendingDate,
            'sym_extra' => $this->extraInformation
        ];
    }
}
