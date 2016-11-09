<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Servico;

class ServicoRepository extends BaseRepository
{
    public function __construct(Servico $servico)
    {
        $this->model = $servico;
    }

}
