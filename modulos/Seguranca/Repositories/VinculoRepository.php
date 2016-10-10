<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Vinculo;

class VinculoRepository extends BaseRepository
{
    public function __construct(Vinculo $vinculo)
    {
        $this->model = $vinculo;
    }
}
