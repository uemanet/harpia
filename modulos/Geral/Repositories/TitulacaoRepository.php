<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Titulacao;

class TitulacaoRepository extends BaseRepository
{
    public function __construct(Titulacao $titulacao)
    {
        $this->model = $titulacao;
    }

    public function verifyTitulacao($titulacaoName)
    {
        $query = $this->model->where('tit_nome', '=', $titulacaoName);

        return $query->first();
    }
}
