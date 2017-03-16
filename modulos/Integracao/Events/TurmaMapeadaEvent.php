<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\Event;

class TurmaMapeadaEvent extends Event
{
  public function __construct($entry, $action = "CREATE")
  {
      parent::__construct($entry, $action);
  }
}
