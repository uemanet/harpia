<?php

namespace Modulos\Academico\Events;

use Modulos\Academico\Models\Grupo;
use Harpia\Event\SincronizacaoEvent;

class UpdateGrupoEvent extends SincronizacaoEvent
{
    public function __construct(Grupo $entry, $extra = null, $version)
    {
        parent::__construct($entry, "UPDATE", $extra, $version);
    }
}
