<?php
namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Grupo;
use Modulos\Core\Repository\BaseRepository;

class GrupoRepository extends BaseRepository
{
    public function __construct(Grupo $grupo)
    {
        $this->model = $grupo;
    }
}