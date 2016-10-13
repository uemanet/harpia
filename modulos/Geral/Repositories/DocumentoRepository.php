<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Documento;

class DocumentoRepository extends BaseRepository
{

    public function __construct(Documento $documento)
    {
        $this->model = $documento;
    }

    public function save($data, $pessoaId)
    {
        
    }
}