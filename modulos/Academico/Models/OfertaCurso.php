<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class OfertaCurso extends BaseModel
{
    protected $table = 'acd_ofertas_cursos';

    protected $primaryKey = 'ofc_id';

    protected $fillable = ['ofc_crs_id', 'ofc_mtc_id', 'ofc_mdl_id','ofc_ano'];

    protected $searchable = [
        'ofc_ano' => 'like'
    ];

    public function curso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Curso', 'grp_trm_id', 'trm_id');
    }

    public function modalidade()
    {
        return $this->belongsTo('Modulos\Academico\Models\Modalidade', 'grp_pol_diretor', 'pol_id');
    }

    public function matriz()
    {
        return $this->belongsTo('Modulos\Academico\Models\Polo', 'grp_pol_diretor', 'pol_id');
    }

    public function polos()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Polo', 'acd_polos_ofertas_cursos', 'poc_pol_id', 'poc_ofc_id');
    }

    public function turmas()
    {
        return $this->hasMany('Modulos\Academico\Models\Turma', 'trm_ofc_id','ofc_id');
    }


}
