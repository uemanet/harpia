<?php

namespace App\Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;

class UsuarioRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
       return 'App\Models\Security\Usuario';
    }
}
