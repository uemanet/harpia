<?php

namespace Modulos\Academico\Events;

use Illuminate\Support\Collection;
use Harpia\Event\SincronizacaoLoteEvent;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;

class DeleteMatriculaDisciplinaLoteEvent extends SincronizacaoLoteEvent
{
    /**
     * DeleteMatriculaDisciplinaLoteEvent constructor.
     *
     * @param Collection $matriculas
     * @param string $action
     * @param null $extra
     * @throws \Exception
     */
    public function __construct(Collection $matriculas, string $action = "DELETE", $extra = null, $version ='v1')
    {

        $this->baseClass = DeleteMatriculaDisciplinaEvent::class;

        foreach ($matriculas as $matricula) {
            if (!$matricula instanceof MatriculaOfertaDisciplina) {
                throw new \Exception("Objeto não é instância de " . MatriculaOfertaDisciplina::class);
            }
        }

        parent::__construct($matriculas, $action, $extra, $version);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return 'local_integracao_batch_unenrol_student_discipline';
    }

    /**
     * @return string
     */
    public function getEndpointV2()
    {
        return 'local_integracao_v2_batch_unenrol_student_discipline';
    }
}
