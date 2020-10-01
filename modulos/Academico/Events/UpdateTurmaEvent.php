<?php

namespace Modulos\Academico\Events;

use Modulos\Academico\Models\Turma;
use Harpia\Event\SincronizacaoEvent;

class UpdateTurmaEvent extends SincronizacaoEvent
{
    public function __construct(Turma $entry, $extra = null, $version)
    {
        parent::__construct($entry, "UPDATE", $extra, $version);
    }
}
