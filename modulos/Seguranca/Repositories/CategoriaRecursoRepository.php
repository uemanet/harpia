<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\CategoriaRecurso;

class CategoriaRecursoRepository extends BaseRepository
{
    public function __construct(CategoriaRecurso $categoriaRecurso)
    {
        $this->model = $categoriaRecurso;
    }
}