<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\Modulo;

class ModuloRepository
{
    protected $model;

    public function __construct(Modulo $modulo)
    {
        $this->model = $modulo;
    }

    public function all()
    {
        return $this->model->paginate(15);
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model;

        if(!is_null($search)) {
            foreach ($search as $key => $value) {
                $result = $result->where($value['field'], $value['type'], $value['term']);
            }
        }

        if(!is_null($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }
}