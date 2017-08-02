<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Permissao;

class PermissaoRepository extends BaseRepository
{
    public function __construct(Permissao $model)
    {
        parent::__construct($model);
    }
}
