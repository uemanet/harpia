<?php


namespace Modulos\Integracao\Listeners;

use Modulos\Integracao\Events\AtualizarSyncDeleteEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class AtualizarSyncDeleteListener
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(AtualizarSyncDeleteEvent $event)
    {
        $data = $event->getData();

        try {
            $this->sincronizacaoRepository->updateSyncMoodle($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }

        return true;
    }
}
