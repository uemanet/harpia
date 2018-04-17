<?php

namespace Modulos\Academico\Events;

use Illuminate\Support\Collection;
use Harpia\Event\SincronizacaoLoteEvent;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;

class CreateMatriculaDisciplinaLoteEvent extends SincronizacaoLoteEvent
{
    /**
     * CreateMatriculaDisciplinaLoteEvent constructor.
     *
     * @param Collection $matriculas
     * @param string $action
     * @param null $extra
     * @throws \Exception
     */
    public function __construct(Collection $matriculas, string $action = "CREATE", $extra = null)
    {
        $this->baseClass = CreateMatriculaDisciplinaEvent::class;

        foreach ($matriculas as $matricula) {
            if (!$matricula instanceof MatriculaOfertaDisciplina) {
                throw new \Exception("Objeto não é instância de " . MatriculaOfertaDisciplina::class);
            }
        }

        parent::__construct($matriculas, $action, $extra);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return 'local_integracao_batch_enrol_student_discipline';
    }
}