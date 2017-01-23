<?php

namespace Modulos\Integracao\Listeners;

use Harpia\Event\Event;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class NovaSyncListener
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(Event $event)
    {
        $entry = $event->getData();

        $date = new \DateTime('NOW');

        $data = [
            'sym_table' => $entry->getTable(),
            'sym_table_id' => $entry->getKey(),
            'sym_action' => 'CREATE',
            'sym_status' => 1,
            'sym_mensagem' => $event->getMessage(),
            'sym_data_envio' => $date->format('Y-m-d H:i:s'),
            'sym_extra' => $event->getExtraInformation()
        ];

        try {
            $this->sincronizacaoRepository->create($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }
}
