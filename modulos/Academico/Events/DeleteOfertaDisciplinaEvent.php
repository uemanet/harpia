<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\OfertaDisciplina;

class DeleteOfertaDisciplinaEvent extends SincronizacaoEvent
{
    public function __construct(OfertaDisciplina $entry, $extra)
    {
        parent::__construct($entry, "DELETE", $extra);
    }
}
