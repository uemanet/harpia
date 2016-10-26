<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Departamento extends BaseModel
{
    protected $table = 'acd_departamentos';

    protected $primaryKey = 'dep_id';

    protected $fillable = [
        'dep_cen_id',
        'dep_prf_diretor',
        'dep_nome'
    ];

    protected $searchable = [
        'dep_nome' => 'like'
    ];

    public function centro()
    {
        return $this->belongsTo('Modulos\Academico\Models\Centro', 'dep_cen_id', 'cen_id');
    }

    public function diretor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Professor', 'dep_prf_diretor', 'prf_id');
    }
}
