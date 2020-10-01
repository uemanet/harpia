<?php

namespace Modulos\Academico\Events;

use Harpia\Event\SincronizacaoEvent;
use Modulos\Academico\Models\Matricula;

class UpdateGrupoAlunoEvent extends SincronizacaoEvent
{
    public function __construct(Matricula $entry, $extra = null, $version)
    {
        parent::__construct($entry, "UPDATE_GRUPO_ALUNO", $extra, $version);
    }
}
