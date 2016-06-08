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

        if(!empty($search)) {
            foreach ($search as $key => $value) {
                $result = $result->where($value['field'], $value['type'], $value['term']);
            }
        }

        if(!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }

    public function paginateRequest(array $requestParameters = null)
    {
        $sort = [];
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
        }

        $search = [];
        if (!empty($requestParameters['mod_nome'])) {
            $search = [
                [
                    'field' => 'mod_nome',
                    'type' => 'like',
                    'term' => $requestParameters['mod_nome']
                ]
            ];
        }

        return $this->paginate($sort, $search);
    }
}