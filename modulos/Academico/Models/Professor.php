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

    public function centro()
    {
        return $this->hasOne('Modulos\Academico\Models\Centro', 'cen_prf_diretor');
    }

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'prf_pes_id');
    }
}
