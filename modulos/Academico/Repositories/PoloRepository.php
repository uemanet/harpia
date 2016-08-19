<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Polo;

class PoloRepository extends BaseRepository
{
    public function __construct(Polo $polo)
    {
        $this->model = $polo;
    }
}
