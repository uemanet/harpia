<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\Event;
use Harpia\Event\EventInterface;
use Modulos\Core\Model\BaseModel;

/**
 * Class AtualizarSyncEvent
 * @package Modulos\Integracao\Events
 */
class AtualizarSyncEvent extends Event
{
    private $action;
    private $status;
    private $sendingDate;
    private $message;
    private $extraInformation;

    /**
     * AtualizarSyncEvent constructor.
     * @param BaseModel $entry
     * @param string $action
     * @param int $status default = 2 for success
     * @param null $message
     * @param null $sendingDate
     * @param null $extraInformation
     */
    public function __construct(BaseModel $entry,
                                $status = 2,
                                $message = null,
                                $action = null,
                                $sendingDate = null,
                                $extraInformation = null)
    {
        $date = new \DateTime('NOW');

        $this->entry = $entry;
        $this->action = $action;
        $this->status = $status;
        $this->message = $message;
        $this->extraInformation = $extraInformation;
        $this->sendingDate = is_null($sendingDate) ? $date->format('Y-m-d H:i:s') : $sendingDate;
    }

    /**
     * Para atualizar na tabela de sincronizacao
     * @return array
     */
    public function getData()
    {
        if ($this->action == null) {
            return [
                'sym_table' => $this->entry->getTable(),
                'sym_table_id' => $this->entry->getKey(),
                'sym_status' => $this->status,
                'sym_mensagem' => $this->message,
                'sym_data_envio' => $this->sendingDate,
                'sym_extra' => $this->extraInformation
            ];
        }

        return [
            'sym_table' => $this->entry->getTable(),
            'sym_table_id' => $this->entry->getKey(),
            'sym_action' => $this->action,
            'sym_status' => $this->status,
            'sym_mensagem' => $this->message,
            'sym_data_envio' => $this->sendingDate,
            'sym_extra' => $this->extraInformation
        ];
    }
}
