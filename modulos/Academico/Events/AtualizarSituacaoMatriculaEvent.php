<?php

namespace Modulos\Academico\Events;

use Harpia\Event\Event;

class AtualizarSituacaoMatriculaEvent extends Event
{
  public function __construct( $entry, $action = "UPDATE_SITUACAO_MATRICULA")
  {
      parent::__construct($entry, $action);
  }
}
