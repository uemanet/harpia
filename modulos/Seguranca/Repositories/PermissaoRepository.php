<?php

namespace App\Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;

class PermissaoRepository extends Repository
{
    /**
     * Specify Models class name.
     *
     * @return string
     */
    public function model()
    {
       return 'App\Models\Security\Permissao';
    }
}
