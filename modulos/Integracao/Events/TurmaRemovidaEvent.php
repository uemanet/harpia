<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\SyncEvent;
use Modulos\Academico\Models\Turma;

class TurmaRemovidaEvent extends SyncEvent
{
    public function __construct(Turma $entry, $extra)
    {
        parent::__construct($entry, "DELETE", $extra);
    }
}
