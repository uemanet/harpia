<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Models\HistoricoMatricula;
use Modulos\Academico\Events\UpdateMatriculaCursoEvent;

class UpdateMatriculaCursoListener
{
    public function handle(UpdateMatriculaCursoEvent $event)
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
