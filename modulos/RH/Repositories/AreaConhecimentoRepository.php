<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\AreaConhecimento;

class AreaConhecimentoRepository extends BaseRepository
{
    public function __construct(AreaConhecimento $areaConhecimento)
    {
        $this->model = $areaConhecimento;
    }
}
