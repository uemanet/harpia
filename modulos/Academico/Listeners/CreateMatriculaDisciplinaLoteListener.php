<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Events\CreateMatriculaDisciplinaLoteEvent;

class CreateMatriculaDisciplinaLoteListener
{
    protected $alunoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->alunoRepository = $alunoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(CreateMatriculaDisciplinaLoteEvent $event)
    {
        // TODO implement
    }
}