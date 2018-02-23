<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class AmbienteTurma extends BaseModel
{
    protected $table = 'int_ambientes_turmas';

    protected $primaryKey = 'atr_id';

    protected $fillable = [
        'atr_trm_id',
        'atr_amb_id'
    ];

    public function turma()
    {
        return $this->belongsTo('Modulos\Academico\Models\Turma', 'atr_trm_id', 'trm_id');
    }
}
