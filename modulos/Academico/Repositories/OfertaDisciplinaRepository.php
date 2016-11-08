<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Core\Repository\BaseRepository;
use Auth;

class OfertaDisciplinaRepository extends BaseRepository
{
    public function __construct(OfertaDisciplina $ofertaDisciplina)
    {
        $this->model = $ofertaDisciplina;
    }

    /**
     * Paginate
     * @param null $sort
     * @param null $search
     * @return mixed
     */
//    public function paginate($sort = null, $search = null)
//    {
//        $result = $this->model
//            ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'ofc_crs_id')
//            ->where('ucr_usr_id', '=', Auth::user()->usr_id);
//
//        if (!empty($search)) {
//            foreach ($search as $key => $value) {
//                switch ($value['type']) {
//                    case 'like':
//                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
//                        break;
//                    default:
//                        $result = $result->where($value['field'], $value['type'], $value['term']);
//                }
//            }
//        }
//
//        if (!empty($sort)) {
//            $result = $result->orderBy($sort['field'], $sort['sort']);
//        }
//
//        return $result->paginate(15);
//    }

    /**
     * Busca todas as matrizes de acordo com o curso informado
     * @param $cursoid
     * @return mixed
     */
    public function findAllByCurso($cursoid)
    {
        return $this->model->where('ofc_crs_id', $cursoid)->get(['ofc_id', 'ofc_ano']);
    }

    /**
     * Busca todas as matrizes de acordo com o curso informado e
     * retorna como lists para popular um field select
     * @param $cursoid
     * @return mixed
     */
    public function listsAllByCurso($cursoid)
    {
        return $this->model->where('ofc_crs_id', $cursoid)->pluck('ofc_ano', 'ofc_id');
    }

    /**
     * Busca uma oferta de curso específica de acordo com o seu Id
     * @param $ofertaid
     * @return mixed
     */
    public function listsAllById($ofertaid)
    {
        return $this->model->where('ofc_id', $ofertaid)->pluck('ofc_ano', 'ofc_id');
    }

    /**
     * Busca um curso específico de acordo com a sua oferta
     * @param $turmadaofertaid
     * @return mixed
     */
    public function listsOfertaByTurma($turmadaofertaid)
    {
        return $this->model->where('ofc_id', $turmadaofertaid)->pluck('ofc_ano', 'ofc_id');
    }
}
