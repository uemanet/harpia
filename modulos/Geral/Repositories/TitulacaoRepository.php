<?php

namespace Modulos\Geral\Repositories;

use Modulos\Geral\Models\Titulacao;
use Modulos\Core\Repository\BaseRepository;

class TitulacaoRepository extends BaseRepository
{
    public function __construct(Titulacao $titulacao)
    {
        parent::__construct($titulacao);
    }

    public function verifyTitulacao($titulacaoName)
    {
        $query = $this->model->where('tit_nome', '=', $titulacaoName);

        return $query->first();
    }
}
