<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Setor extends BaseModel
{

    protected $table = 'gra_setores';

    protected $primaryKey = 'set_id';

    protected $fillable = [
        'set_nome'
    ];
}
