<?php

namespace Modulos\Academico\Events;

use Harpia\Event\Event;

class AlterarProfessorOfertaDisciplinaEvent extends Event
{
    public function __construct($entry, $action = "UPDATE_PROFESSOR_OFERTA_DISCIPLINA")
    {
        parent::__construct($entry, $action);
    }
}
