<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Permissao;

class PermissaoRepository extends BaseRepository
{
    public function __construct(Permissao $permissao)
    {
        $this->model = $permissao;
    }
}
