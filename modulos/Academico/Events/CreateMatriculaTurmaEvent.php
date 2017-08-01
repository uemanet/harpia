<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;

class CreateMatriculaTurmaEvent extends SincronizacaoEvent
{
    public function __construct($entry)
    {
        parent::__construct($entry, "CREATE");
    }
}
