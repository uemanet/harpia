<?php

namespace Modulos\Integracao\Listeners;

use Harpia\Event\Event;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class NovaSyncUpdatePessoaListener
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $repository)
    {
        $this->sincronizacaoRepository = $repository;
    }

    public function handle(Event $event)
    {
        $entry = $event->getData();
        // dd($event->getExtra());
        $data = [
            'sym_table' => $entry->getTable(),
            'sym_table_id' => $entry->getKey(),
            'sym_action' => $event->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $event->getExtra()
        ];

        try {
            $this->sincronizacaoRepository->create($data);
        } catch (\Exception $e) {
            // Interrompe a propagacao do evento
            return false;
        }

        return true;
    }
}
