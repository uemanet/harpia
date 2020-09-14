<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Matriculas\Models\SeletivoUser;

class SeletivoUserRepository extends BaseRepository
{
    public function __construct(SeletivoUser $seletivo_user)
    {
        $this->model = $seletivo_user;
    }
}
