<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;

class HistoricoDefinitivoRepository extends BaseRepository
{
    public function __construct(Matricula $model)
    {
        $this->model = $model;
    }

    public function getGradeCurricularByMatricula($matriculaId)
    {
    }
}
