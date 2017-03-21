<?php

namespace Modulos\Academico\Events;

use Harpia\Event\Event;

class TutorVinculadoEvent extends Event
{
    public function __construct($entry, $action = "CREATE")
    {
        parent::__construct($entry, $action);
    }
}
