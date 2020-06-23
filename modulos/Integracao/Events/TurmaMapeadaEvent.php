<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\Turma;

class TurmaMapeadaEvent extends SincronizacaoEvent
{
    public function __construct(Turma $entry, $extra = null, $version)
    {
        parent::__construct($entry, "CREATE", $extra, $version);
    }
}
