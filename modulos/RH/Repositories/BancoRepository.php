<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Banco;

class BancoRepository extends BaseRepository
{
    public function __construct(Banco $banco)
    {
        $this->model = $banco;
    }

}
