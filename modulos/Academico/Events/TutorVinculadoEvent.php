<?php

namespace Modulos\Academico\Events;

use Harpia\Event\Event;
use Modulos\Core\Model\BaseModel;

class TutorVinculadoEvent extends Event
{
    private $grupo;

    public function __construct(BaseModel $entry, BaseModel $grupo)
    {
        $this->grupo = $grupo;
        parent::__construct($entry);
    }

    public function getGroup()
    {
        return $this->grupo;
    }
}
