<?php


namespace Modulos\Integracao\Listeners;

use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class UpdateSincronizacaoListener
{
    protected $sincronizacaoRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(UpdateSincronizacaoEvent $event)
    {
        try {
            $data = $event->getData();
            $this->sincronizacaoRepository->updateSyncMoodle($data);
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
