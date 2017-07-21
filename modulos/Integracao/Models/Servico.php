<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class Servico extends BaseModel
{
    protected $table = 'int_servicos';

    protected $primaryKey = 'ser_id';

    protected $fillable = [
        'ser_nome',
        'ser_slug'
    ];

    protected $searchable = [
        'ser_nome' => 'like'
    ];
}
