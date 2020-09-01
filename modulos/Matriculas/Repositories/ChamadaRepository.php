<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Matriculas\Models\Chamada;

class ChamadaRepository extends BaseRepository
{
    public function __construct(Chamada $chamada)
    {
        parent::__construct($chamada);
    }
}