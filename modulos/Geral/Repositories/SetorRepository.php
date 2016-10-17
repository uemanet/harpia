<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Setor;

class SetorRepository extends BaseRepository
{
    public function __construct(Setor $setor)
    {
        $this->model = $setor;
    }
}
