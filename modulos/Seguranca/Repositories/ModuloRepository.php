<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Modulo;

class ModuloRepository extends BaseRepository
{
    public function __construct(Modulo $modulo)
    {
        $this->model = $modulo;
    }

    public function create(array $data)
    {
        $data['mod_rota'] = mb_strtolower(preg_replace('/\s+/', '', $data['mod_rota']));

        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $data['mod_rota'] = mb_strtolower(preg_replace('/\s+/', '', $data['mod_rota']));

        return $this->model->where($attribute, '=', $id)->update($data);
    }
}
