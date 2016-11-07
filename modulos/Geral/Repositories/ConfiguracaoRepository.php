<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Configuracao;

class ConfiguracaoRepository extends BaseRepository
{
    public function __construct(Configuracao $configuracao)
    {
        $this->model = $configuracao;
    }
}