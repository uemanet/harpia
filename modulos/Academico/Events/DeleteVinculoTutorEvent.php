<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\TutorGrupo;

class DeleteVinculoTutorEvent extends SincronizacaoEvent
{
    public function __construct(TutorGrupo $entry)
    {
        parent::__construct($entry, "DELETE");
    }
}
