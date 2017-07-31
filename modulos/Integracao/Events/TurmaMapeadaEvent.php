<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\SyncEvent;
use Modulos\Academico\Models\Turma;

class TurmaMapeadaEvent extends SyncEvent
{
    public function __construct(Turma $entry)
    {
        parent::__construct($entry, "CREATE");
    }
}
