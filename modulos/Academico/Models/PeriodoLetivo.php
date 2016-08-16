<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class PeriodoLetivo extends BaseModel
{
    protected $table = 'acd_periodos_letivos';

    protected $primaryKey = 'per_id';

    protected $fillable = ['per_inicio', 'per_fim'];

    protected $searchable = [
        'per_inicio' => 'like',
    ];
}
