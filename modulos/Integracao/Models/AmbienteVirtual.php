<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class AmbienteVirtual extends BaseModel
{
    protected $table = 'int_ambientes_virtuais';

    protected $primaryKey = 'amb_id';

    protected $fillable = [
        'amb_nome',
        'amb_versao',
        'amb_url'
    ];

    protected $searchable = [
        'amb_nome' => 'like'
    ];

}
