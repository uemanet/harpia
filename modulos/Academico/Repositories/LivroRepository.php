<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Livro;
use Modulos\Core\Repository\BaseRepository;

class LivroRepository extends BaseRepository
{
    public function __construct(Livro $livro)
    {
        $this->model = $livro;
    }
}
