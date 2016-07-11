<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Polo extends BaseModel
{
    protected $table = 'gra_polos';

    protected $primaryKey = 'pol_id';

    protected $fillable = [
        'pol_nome'
    ];

    protected $searchable = [
        'pol_nome' => 'like'
    ];
}