<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\SalarioColaborador;

class SalarioColaboradorRepository extends BaseRepository
{
    public function __construct(SalarioColaborador $salario)
    {
        $this->model = $salario;
    }
}
