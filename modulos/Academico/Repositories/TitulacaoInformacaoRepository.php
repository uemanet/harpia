<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\TitulacaoInformacao;

class TitulacaoInformacaoRepository extends BaseRepository
{
    public function __construct(TitulacaoInformacao $titulacaoInformacao)
    {
        $this->model = $titulacaoInformacao;
    }
}
