<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\ColaboradorFuncao;

class ColaboradorFuncaoRepository extends BaseRepository
{
    public function __construct(ColaboradorFuncao $colaborador_funcao)
    {
        $this->model = $colaborador_funcao;
    }

}
