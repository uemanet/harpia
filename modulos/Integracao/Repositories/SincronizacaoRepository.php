<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Sincronizacao;

class SincronizacaoRepository extends BaseRepository
{
    public function __construct(Sincronizacao $sincronizacao)
    {
        $this->model = $sincronizacao;
    }
}
