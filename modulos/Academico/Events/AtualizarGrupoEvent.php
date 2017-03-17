<?php

namespace Modulos\Academico\Events;

use Harpia\Event\Event;
use Modulos\Core\Model\BaseModel;

class AtualizarGrupoEvent extends Event
{
    public function __construct(BaseModel $entry, $action = "UPDATE")
    {
        parent::__construct($entry, $action);
    }
}
