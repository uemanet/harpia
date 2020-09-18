<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\VinculoFontePagadora;

class VinculoFontePagadoraRepository extends BaseRepository
{
    public function __construct(VinculoFontePagadora $vinculo)
    {
        $this->model = $vinculo;
    }
}
