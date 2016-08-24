<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\MatrizCurricular;

class MatrizCurricularRepository extends BaseRepository
{
    public function __construct(MatrizCurricular $matrizcurricular)
    {
        $this->model = $matrizcurricular;
    }

    /**
     * Busca todas as matrizes de acordo com o curso informado
     *
     * @param $cursoid
     *
     * @return mixed
     */
    public function findAllByCurso($cursoid)
    {
        return $this->model->where('mtc_crs_id', $cursoid)->get(['mtc_id', 'mtc_descricao']);
    }

    /**
     * Busca todas as matrizes de acordo com o curso informado e retorna como lists para popular um field select
     *
     * @param $cursoid
     *
     * @return mixed
     */
    public function listsAllByCurso($cursoid)
    {
        return $this->model->where('mtc_crs_id', $cursoid)->lists('mtc_descricao', 'mtc_id');
    }
}
