<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\Event;
use Modulos\Core\Model\BaseModel;

class MapearNotasEvent extends Event
{
    public function __construct(BaseModel $entry, $action)
    {
        parent::__construct($entry, $action);
    }
}
