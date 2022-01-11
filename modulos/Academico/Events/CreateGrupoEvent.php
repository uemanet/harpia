<?php

namespace Modulos\Academico\Events;

use Modulos\Academico\Models\Grupo;
use Harpia\Event\SincronizacaoEvent;

class CreateGrupoEvent extends SincronizacaoEvent
{
    public function __construct(Grupo $entry, $extra = null, $version)
    {
        parent::__construct($entry, "CREATE", $extra, $version);
    }
}
