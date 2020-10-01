<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\OfertaDisciplina;

class UpdateProfessorDisciplinaEvent extends SincronizacaoEvent
{
    public function __construct(OfertaDisciplina $entry, $extra = null, $version)
    {
        parent::__construct($entry, "UPDATE_PROFESSOR_OFERTA_DISCIPLINA", $extra, $version);
    }
}
