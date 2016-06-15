<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Funcao extends BaseModel
{
    protected $table = 'gra_funcao';

    protected $primaryKey = 'fun_id';

    protected $fillable = [
        'fun_nome'
    ];
}
