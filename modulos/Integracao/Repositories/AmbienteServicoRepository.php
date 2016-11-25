<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteServico;

class AmbienteServicoRepository extends BaseRepository
{
    public function __construct(AmbienteServico $ambienteservico)
    {
        $this->model = $ambienteservico;
    }
}
