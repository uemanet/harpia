<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Professor extends BaseModel
{
    protected $table = 'acd_professores';

    protected $primaryKey = 'prf_id';

    protected $fillable = [
        'prf_pes_id',
        'prf_matricula'
    ];

    protected $searchable = [
        'prf_id' => '='
    ];
}
