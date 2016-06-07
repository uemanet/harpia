<?php

namespace Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;

class ModuloRepository extends Repository
{
    /**
     * Specify Models class name.
     *
     * @return string
     */
    public function model()
    {
       return 'Modulos\Seguranca\Models\Modulo';
    }
}