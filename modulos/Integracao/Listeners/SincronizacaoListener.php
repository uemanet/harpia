<?php

namespace Modulos\Integracao\Listeners;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class SincronizacaoListener
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $repository)
    {
        $this->sincronizacaoRepository = $repository;
    }

    public function handle(SincronizacaoEvent $event)
    {
        try {
            if ($event->isFirstAttempt()) {
                $entry = $event->getData();

                $data = [
                    'sym_table' => $entry->getTable(),
                    'sym_table_id' => $entry->getKey(),
                    'sym_action' => $event->getAction(),
                    'sym_status' => 1,
                    'sym_mensagem' => null,
                    'sym_data_envio' => null,
                    'sym_extra' => $event->getExtra()
                ];

                $this->sincronizacaoRepository->create($data);
            }
            // @codeCoverageIgnoreStart
            // Exception tem efeitos limitados ao ambiente de debug
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        } finally {
            // @codeCoverageIgnoreEnd
            // Mantem a propagacao do evento
            return true;
        }
    }
}
