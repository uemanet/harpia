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

    public function create(array $data)
    {
        $data['crs_data_autorizacao']= Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $data['crs_data_autorizacao']= Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }
}
