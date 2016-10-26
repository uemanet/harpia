<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Modalidade;

class ModalidadeRepository extends BaseRepository
{
    public function __construct(Modalidade $modalidade)
    {
        $this->model = $modalidade;
    }
}
