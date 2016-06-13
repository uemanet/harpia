<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Pessoa;

class PessoaRepository extends BaseRepository
{
    public function __construct(Pessoa $pessoa)
    {
        $this->model = $pessoa;
    }
}
