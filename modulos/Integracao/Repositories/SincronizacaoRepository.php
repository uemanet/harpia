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
}
