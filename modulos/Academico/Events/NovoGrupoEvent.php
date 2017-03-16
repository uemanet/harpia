<?php

namespace Modulos\Academico\Events;

use Harpia\Event\Event;

class NovoGrupoEvent extends Event
{
  public function __construct($entry, $action = "CREATE")
  {
      parent::__construct($entry, $action);
  }
}
