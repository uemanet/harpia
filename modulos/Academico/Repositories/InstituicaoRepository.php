<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Instituicao;
use Modulos\Core\Repository\BaseRepository;

class InstituicaoRepository extends BaseRepository
{
    public function __construct(Instituicao $instituicao)
    {
        $this->model = $instituicao;
    }
}
