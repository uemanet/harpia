<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Centro;
use Modulos\Core\Repository\BaseRepository;

class CentroRepository extends BaseRepository
{
    public function __construct(Centro $centro)
    {
        parent::__construct($centro);
    }
}
