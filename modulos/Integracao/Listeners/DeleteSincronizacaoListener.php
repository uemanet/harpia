<?php


namespace Modulos\Integracao\Listeners;

use Modulos\Integracao\Events\DeleteSincronizacaoEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class DeleteSincronizacaoListener
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(DeleteSincronizacaoEvent $event)
    {
        try {
            $data = $event->getData();
            $this->sincronizacaoRepository->updateSyncMoodle($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            // Mantem a propagacao do evento
            return true;
        }
    }
}
