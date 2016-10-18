<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\ModuloDisciplina;

class ModuloDisciplinaRepository extends BaseRepository
{
    public function __construct(ModuloDisciplina $modulodisciplina)
    {
        $this->model = $modulodisciplina;
    }   

}
