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
        if (empty($data['ctr_referencia'])) {
            unset($data['ctr_referencia']);
        }

        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        if (empty($data['ctr_referencia'])) {
            unset($data['ctr_referencia']);
        }

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * Busca todas as categorias de acordo com o modulo informado
     *
     * @param $moduloid
     *
     * @return mixed
     */
    public function findAllByModulo($moduloid)
    {
        return $this->model->where('ctr_mod_id', $moduloid)->get(['ctr_id', 'ctr_nome']);
    }

    /**
     * Busca todas as categorias de acordo com o modulo informado e retorna como lists para popular um field select
     *
     * @param $moduloid
     *
     * @return mixed
     */
    public function listsAllByModulo($moduloid)
    {
        return $this->model->where('ctr_mod_id', $moduloid)->lists('ctr_nome', 'ctr_id');
    }
}
