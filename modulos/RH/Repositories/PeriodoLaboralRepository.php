<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\PeriodoLaboral;

class PeriodoLaboralRepository extends BaseRepository
{
    public function __construct(PeriodoLaboral $periodolaboral)
    {
        $this->model = $periodolaboral;
    }

    /**
     * Busca períodos laborais que estão entre as dadas recebidas.
     */
    public function buscaPeriodosLaboraisEntreDatas($dataInicial, $dataFinal)
    {
       return $this->model->where('pel_inicio', '<=', $dataInicial)->where('pel_termino', '>=', $dataFinal)->get();
    }
}
