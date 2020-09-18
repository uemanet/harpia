<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class Calendario extends BaseModel
{
    protected $table = 'reh_calendarios';

    protected $primaryKey = 'cld_id';

    protected $fillable = [
        'cld_nome',
        'cld_data',
        'cld_observacao',
        'cld_tipo_evento'
    ];

}
