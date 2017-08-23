<?php
namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;

class CreateMatriculaDisciplinaEvent extends SincronizacaoEvent
{
    public function __construct(MatriculaOfertaDisciplina $entry, $extra = null)
    {
        parent::__construct($entry, "CREATE", $extra);
    }
}
