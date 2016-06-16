<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Funcao extends BaseModel
{
    protected $table = 'gra_funcoes';

    protected $primaryKey = 'fun_id';

    protected $fillable = [
        'fun_nome'
    ];
}
