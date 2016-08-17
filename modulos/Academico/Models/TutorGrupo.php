<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class TutorGrupo extends BaseModel
{
    protected $table = 'acd_tutores_grupos';

    protected $primaryKey = 'ttg_id';

    protected $fillable = [
        'ttg_tut_id',
        'ttg_grp_id',
        'ttg_tipo_tutoria'
    ];

    protected $searchable = [
        'ttg_tipo_tutoria' => 'like'
    ];

    public function tutor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Tutor', 'ttg_tut_id', 'tut_id');
    }

    public function grupo()
    {
        return $this->belongsTo('Modulos\Academico\Models\Grupo', 'ttg_grp_id', 'grp_id');
    }
}
