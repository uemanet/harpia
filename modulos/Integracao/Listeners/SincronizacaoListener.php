<?php

namespace Modulos\Integracao\Listeners;

use Harpia\Event\Contracts\SincronizacaoLoteInterface;
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
            // Migracao em lote
            if ($event instanceof SincronizacaoLoteInterface) {
                $class = $event->getBaseClass();

                // Salva cada item de uma migracao em lote como uma migracao individual na tabela de sincronizacao
                foreach ($event->getItemsAsEvents() as $itemEvent) {
                    $data = $this->getSyncData($itemEvent);
                    $this->sincronizacaoRepository->create($data);

                    unset($itemEvent);
                }

                return true; // Passa para os demais listeners
            }

            if ($event->isFirstAttempt()) {
                $data = $this->getSyncData($event);
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

    protected function getSyncData(SincronizacaoEvent $event): array
    {
        $entry = $event->getData();

        return [
            'sym_table' => $entry->getTable(),
            'sym_table_id' => $entry->getKey(),
            'sym_action' => $event->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $event->getExtra()
        ];
    }
}
