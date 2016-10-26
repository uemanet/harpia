<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\TipoDocumento;

class TipoDocumentoRepository extends BaseRepository
{
    public function __construct(TipoDocumento $tipo)
    {
        $this->model = $tipo;
    }
}
