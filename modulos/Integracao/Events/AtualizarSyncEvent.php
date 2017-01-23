<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\Event;

/**
 * Class AtualizarSyncEvent
 * @package Modulos\Integracao\Events
 */
class AtualizarSyncEvent extends Event
{
    private $table;
    private $tableId;
    private $action;
    private $status;
    private $message;
    private $sendingDate;
    private $extraInformation;


    /**
     * AtualizarSyncEvent constructor.
     * @param $table
     * @param $tableId
     * @param string $action
     * @param int $status default = 2 for success
     * @param null $message
     * @param null $sendingDate
     * @param null $extraInformation
     */
    public function __construct($table,
                                $tableId,
                                $action = 'UPDATE',
                                $status = 2,
                                $message = null,
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
        $this->sendingDate = $date->format('Y-m-d H:i:s');
    }

    /**
     * Para atualizar na tabela de sincronizacao
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
