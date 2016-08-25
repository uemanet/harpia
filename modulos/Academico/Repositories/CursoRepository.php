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
}
