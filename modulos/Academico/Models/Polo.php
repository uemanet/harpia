<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Polo extends BaseModel
{
    protected $table = 'acd_polos';

    protected $primaryKey = 'pol_id';

    protected $fillable = [
        'pol_nome'
    ];

    protected $searchable = [
        'pol_nome' => 'like'
    ];
}
