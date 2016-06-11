<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Perfil;

class PerfilRepository extends BaseRepository
{
    public function __construct(Perfil $perfil)
    {
        $this->model = $perfil;
    }
}