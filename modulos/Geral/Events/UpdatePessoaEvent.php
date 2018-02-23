<?php

namespace Modulos\Geral\Events;

use Modulos\Geral\Models\Pessoa;
use Harpia\Event\SincronizacaoEvent;

class UpdatePessoaEvent extends SincronizacaoEvent
{
    public function __construct(Pessoa $entry, $extra = null)
    {
        parent::__construct($entry, "UPDATE", $extra);
    }
}
