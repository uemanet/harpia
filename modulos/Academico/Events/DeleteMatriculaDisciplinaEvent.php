<?php
namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;

class DeleteMatriculaDisciplinaEvent extends SincronizacaoEvent
{
    public function __construct(MatriculaOfertaDisciplina $entry, $extra, $version)
    {

        parent::__construct($entry, "DELETE", $extra, $version);
    }
}
