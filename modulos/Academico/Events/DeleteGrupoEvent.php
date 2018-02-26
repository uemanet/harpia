<?php

namespace Modulos\Academico\Events;

use Modulos\Academico\Models\Grupo;
use Harpia\Event\SincronizacaoEvent;

class DeleteGrupoEvent extends SincronizacaoEvent
{
    public function __construct(Grupo $entry, $extra)
    {
        parent::__construct($entry, "DELETE", $extra);
    }
}
