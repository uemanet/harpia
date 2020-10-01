<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class Banco extends BaseModel
{
    protected $table = 'reh_bancos';

    protected $primaryKey = 'ban_id';

    protected $fillable = [
        'ban_nome',
        'ban_codigo',
        'ban_sigla'
    ];

    protected $searchable = [
        'ban_nome' => 'like'
    ];

}
