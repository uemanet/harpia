<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Titulacao;

class TitulacaoRepository extends BaseRepository
{
    public function __construct(Titulacao $titulacao)
    {
        $this->model = $titulacao;
    }
}
