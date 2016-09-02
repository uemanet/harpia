<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\MatrizCurricular;
use Carbon\Carbon;

class MatrizCurricularRepository extends BaseRepository
{
    public function __construct(MatrizCurricular $matrizCurricular)
    {
        $this->model = $matrizCurricular;
    }

    /**
     * Formata datas pt_BR para default MySQL
     * para update de registros
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data['mtc_data'] = Carbon::createFromFormat('d/m/Y', $data['mtc_data'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * Pagina apenas as matrizes pelo curso
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByCurso($cursoid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('mtc_crs_id', '=', $cursoid)
                               ->orderBy($sort['field'], $sort['sort'])
                               ->paginate(15);
        }
        return $this->model->where('mtc_crs_id', '=', $cursoid)->paginate(15);
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
