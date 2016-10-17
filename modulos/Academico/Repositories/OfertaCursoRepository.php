<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\OfertaCurso;

class OfertaCursoRepository extends BaseRepository
{
    public function __construct(OfertaCurso $ofertacurso)
    {
        $this->model = $ofertacurso;
        //dd($this);
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
        return $this->model->where('ofc_crs_id', $cursoid)->get(['ofc_id', 'ofc_ano']);
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
        return $this->model->where('ofc_crs_id', $cursoid)->pluck('ofc_ano', 'ofc_id');
    }

    /**
     * Busca uma oferta de curso específica de acordo com o seu Id
     *
     * @param $ofertaid
     *
     * @return mixed
     */
    public function listsAllById($ofertaid)
    {
        return $this->model->where('ofc_id', $ofertaid)->pluck('ofc_ano', 'ofc_id');
    }

    /**
     * Busca um curso específico de acordo com a sua oferta
     *
     * @param $turmadaofertaid
     *
     * @return mixed
     */
    public function listsOfertaByTurma($turmadaofertaid)
    {
        return $this->model->where('ofc_id', $turmadaofertaid)->pluck('ofc_ano', 'ofc_id');
    }
}
