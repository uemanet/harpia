<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\OfertaDisciplina;

class CreateOfertaDisciplinaEvent extends SincronizacaoEvent
{
    public function __construct(OfertaDisciplina $entry, $extra = null)
    {
        parent::__construct($entry, "CREATE", $extra);
    }
}
