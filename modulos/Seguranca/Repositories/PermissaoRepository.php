<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\Permissao;
use Modulos\Core\Repository\BaseRepository;

class PermissaoRepository extends BaseRepository
{
    public function __construct(Permissao $model)
    {
        parent::__construct($model);
    }
}
