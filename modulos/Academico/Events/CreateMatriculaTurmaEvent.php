<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\Matricula;

class CreateMatriculaTurmaEvent extends SincronizacaoEvent
{
    public function __construct(Matricula $entry, $extra = null)
    {
        parent::__construct($entry, "CREATE", $extra);
    }
}
