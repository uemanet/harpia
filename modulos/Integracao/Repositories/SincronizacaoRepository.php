<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Sincronizacao;

class SincronizacaoRepository extends BaseRepository
{
    public function __construct(Sincronizacao $sincronizacao)
    {
        $this->model = $sincronizacao;
    }

    public function update(array $data, $id = null, $attribute = "id")
    {
        return $this->model
            ->where('sym_table', '=', $data['sym_table'])
            ->where('sym_table_id', '=', $data['sym_table_id'])
            ->update($data);
    }

    public function findBy($options)
    {
        $query = $this->model;

        if(!empty($options))
        {
            foreach ($options as $key => $value)
            {
                $query = $query->where($key, '=',$value);
            }

            return $query->get();
        }

        return $query->all();
    }
}
