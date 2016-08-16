<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Centro extends BaseModel
{
    protected $table = 'acd_centros';

    protected $primaryKey = 'cen_id';

    protected $fillable = ['cen_prf_diretor', 'cen_nome','cen_sigla'];

    protected $searchable = [
        'cen_nome' => 'like',
        'cen_sigla' => 'like'
    ];

    public function departamentos()
    {
        $this->hasMany('Modulos\Academico\Models\Departamento', 'dep_cen_id', 'cen_id');
    }

    public function diretor()
    {
        $this->belongsTo('Modulos\Academico\Models\Professor', 'dep_prf_diretor', 'prf_id');
    }
}
