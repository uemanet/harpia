<?php

namespace Modulos\Academico\Events;

class AtualizarMatriculaCursoEvent
{
    const POLO = "polo";
    const GRUPO = "grupo";
    const SITUACAO = "situacao";

    private $matricula;
    private $tipoAlteracao;
    private $observacao;

    public function __construct($entry, $tipoAlteracao = AtualizarMatriculaCursoEvent::POLO, $observacao = "")
    {
        $this->matricula = $entry;
        $this->tipoAlteracao = $tipoAlteracao;
        $this->observacao = $observacao;
    }

    /**
     * @return mixed
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @return string
     */
    public function getTipoAlteracao()
    {
        if ($this->tipoAlteracao == AtualizarMatriculaCursoEvent::POLO) {
            return "mudanca_polo";
        };

        if ($this->tipoAlteracao == AtualizarMatriculaCursoEvent::GRUPO) {
            return "mudanca_grupo";
        };

        return "alteracao_status";
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }
}
