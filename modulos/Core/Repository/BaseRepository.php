<?php

namespace Modulos\Core\Repository;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(\Modulos\Core\Model\BaseModel $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
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

        $searchable = $this->model->searchable();
        $search = [];
        foreach ($requestParameters as $key => $value) {
            if (array_key_exists($key, $searchable) and !empty($value)) {
                $search[] = [
                    'field' => $key,
                    'type' => $searchable[$key],
                    'term' => $value
                ];
            }
        }

        return $this->paginate($sort, $search);
    }
}