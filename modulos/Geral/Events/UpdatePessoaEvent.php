<?php

namespace Modulos\Geral\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Core\Model\BaseModel;

class UpdatePessoaEvent extends SincronizacaoEvent
{
    public function __construct(BaseModel $entry, $extra = null)
    {
        parent::__construct($entry, "UPDATE", $extra);
    }
}
