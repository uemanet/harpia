<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\AtividadeExtraColaborador;

class AtividadeExtraColaboradorRepository extends BaseRepository
{
    public function __construct(AtividadeExtraColaborador $atividade_extra)
    {
        $this->model = $atividade_extra;
    }

}
