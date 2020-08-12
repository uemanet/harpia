<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\ContaColaborador;

class ContaColaboradorRepository extends BaseRepository
{
    public function __construct(ContaColaborador $conta_extra)
    {
        $this->model = $conta_extra;
    }

}
