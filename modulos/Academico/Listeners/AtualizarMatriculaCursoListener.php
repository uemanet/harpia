<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\AtualizarMatriculaCursoEvent;
use Modulos\Academico\Models\HistoricoMatricula;

class AtualizarMatriculaCursoListener
{
    public function handle(AtualizarMatriculaCursoEvent $event)
    {
        $historico = new HistoricoMatricula();

        $matricula = $event->getMatricula();

        $data = new \DateTime('NOW');
        $historico->hmt_mat_id = $matricula->mat_id;
        $historico->hmt_data = $data->format('Y-m-d');
        $historico->hmt_tipo = $event->getTipoAlteracao();
        $historico->hmt_observacao = $event->getObservacao();

        $historico->save();
    }
}
