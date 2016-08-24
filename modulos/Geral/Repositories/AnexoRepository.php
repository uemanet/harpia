<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Anexo;

class AnexoRepository extends BaseRepository
{
    public function __construct(Anexo $anexo )
    {
        $this->model = $anexo;
    }
}
