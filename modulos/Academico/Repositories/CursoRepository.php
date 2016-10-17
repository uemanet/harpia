<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Curso;
use Carbon\Carbon;

class CursoRepository extends BaseRepository
{
    public function __construct(Curso $curso)
    {
        $this->model = $curso;
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
        $data['crs_data_autorizacao']= Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * Busca um curso específico de acordo com a sua oferta
     *
     * @param $cursodaofertaid
     *
     * @return mixed
     */
    public function listsCursoByOferta($cursodaofertaid)
    {
        return $this->model->where('crs_id', $cursodaofertaid)->pluck('crs_nome', 'crs_id');
    }

    /**
     * Busca um curso específico de acordo com a sua matriz
     *
     * @param $matrizId
     *
     * @return mixed
     */
    public function listsCursoByMatriz($matrizId)
    {
        return $this->model->where('crs_id', $matrizId)->pluck('crs_nome', 'crs_id');
    }
}
