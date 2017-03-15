<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\OfertaCurso;
use Auth;

class OfertaCursoRepository extends BaseRepository
{
    public function __construct(OfertaCurso $ofertacurso)
    {
        $this->model = $ofertacurso;
    }

    /**
     * Paginate
     * @param null $sort
     * @param null $search
     * @return mixed
     */
    public function paginate($sort = null, $search = null)
    {
        $result = $this->model;

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }

    /**
     * Busca todas as ofertas de curso de acordo com o curso informado
     * @param $cursoid
     * @return mixed
     */
    public function findAllByCurso($cursoid)
    {
        return $this->model
                    ->join('acd_modalidades', 'ofc_mdl_id', '=', 'mdl_id')
                    ->where('ofc_crs_id', $cursoid)
                    ->get(['ofc_id', 'ofc_ano', 'mdl_nome']);
    }

    /**
     * Busca todas as ofertas de curso de acordo com o curso informado sem a modalidade presencial
     * @param $cursoid
     * @return mixed
     */
    public function findAllByCursowithoutpresencial($cursoid)
    {
        return $this->model
                    ->join('acd_modalidades', 'ofc_mdl_id', '=', 'mdl_id')
                    ->where([
                        ['ofc_crs_id', '=', $cursoid],
                        ['ofc_mdl_id', '<>', 1]
                    ])
                    ->get(['ofc_id', 'ofc_ano', 'mdl_nome']);
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

    /**
     * Cria uma nova oferta de curso, de acordo com regras de validação
     * @param array $ofertaCurso
     * @return mixed
     */

    public function create(array $data)
    {
        // verifica se existe um registro com mesmo ano e modalidade
        $entry = $this->model
                        ->where([
                            ['ofc_ano', '=', $data['ofc_ano']],
                            ['ofc_mdl_id', '=', $data['ofc_mdl_id']]
                        ])->first();

        if (!$entry) {
            $oferta = new OfertaCurso();

            $oferta->ofc_crs_id = $data['ofc_crs_id'];
            $oferta->ofc_mtc_id = $data['ofc_mtc_id'];
            $oferta->ofc_mdl_id = $data['ofc_mdl_id'];
            $oferta->ofc_ano = $data['ofc_ano'];

            $oferta->save();

            return $oferta;
        }

        return null;
    }
}
