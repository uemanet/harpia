<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\CategoriaRecurso;

class CategoriaRecursoRepository extends BaseRepository
{
    public function __construct(CategoriaRecurso $categoriaRecurso)
    {
        $this->model = $categoriaRecurso;
    }

    public function create(array $data)
    {
        if(empty($data['ctr_referencia'])) {
            unset($data['ctr_referencia']);
        }

        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        if(empty($data['ctr_referencia'])) {
            unset($data['ctr_referencia']);
        }

        return $this->model->where($attribute, '=', $id)->update($data);
    }
}