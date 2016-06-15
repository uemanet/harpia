<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Funcao;

class FuncaoRepository extends BaseRepository
{
    public function __construct(Funcao $funcao)
    {
        $this->model = $funcao;
    }
}