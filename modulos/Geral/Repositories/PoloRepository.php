<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Polo;

class PoloRepository extends BaseRepository
{
    public function __construct(Polo $polo)
    {
        $this->model = $polo;
    }
}
