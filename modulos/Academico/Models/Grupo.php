<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Grupo extends BaseModel
{
    protected $table = 'acd_grupos';

    protected $primaryKey = 'grp_id';

    protected $fillable = [
        'grp_trm_id',
        'grp_pol_id',
        'grp_nome'
    ];

    protected $searchable = [
        'grp_nome' => 'like'
    ];

    public function turma()
    {
        return $this->belongsTo('Modulos\Academico\Models\Turma', 'grp_trm_id', 'trm_id');
    }

    public function polo()
    {
        return $this->belongsTo('Modulos\Academico\Models\Polo', 'grp_pol_id', 'pol_id');
    }

    public function movimentacoes()
    {
        return $this->hasMany('Modulos\Academico\Models\TutorGrupo', 'ttg_grp_id', 'grp_id');
    }
}
