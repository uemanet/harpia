<?php

namespace Modulos\Academico\Events;

use Modulos\Academico\Models\Grupo;
use Harpia\Event\SincronizacaoEvent;

class UpdateGrupoEvent extends SincronizacaoEvent
{
    public function __construct(Grupo $entry)
    {
        parent::__construct($entry, "UPDATE");
    }
}
