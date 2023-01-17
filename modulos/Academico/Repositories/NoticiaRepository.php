<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Noticia;
use Modulos\Core\Repository\BaseRepository;

class NoticiaRepository extends BaseRepository
{
    public function __construct(Noticia $noticia)
    {
        $this->model = $noticia;
    }
}
