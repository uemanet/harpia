<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\Turma;

class TurmaRemovidaEvent extends SincronizacaoEvent
{
    public function __construct(Turma $entry, $extra)
    {
        parent::__construct($entry, "DELETE", $extra);
    }
}
