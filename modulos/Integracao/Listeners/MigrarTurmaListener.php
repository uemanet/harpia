<?php

namespace Modulos\Integracao\Listeners;

use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class MigrarTurmaListener
{
    public $repo;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->repo = $ambienteVirtualRepository;
    }

    public function handle(TurmaMapeadaEvent $event)
    {
        dd($this->repo->getAmbienteByTurma($event->getData()->trm_id));
    }
}
