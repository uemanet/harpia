<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;

class CreateMatriculaTurmaEvent extends SincronizacaoEvent
{
    public function __construct(MatriculaOfertaDisciplina $entry)
    {
        parent::__construct($entry, "CREATE");
    }
}
