<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Polo extends BaseModel
{
    protected $table = 'acd_polos';

    protected $primaryKey = 'pol_id';

    protected $fillable = [
        'pol_nome',
        'pol_itt_id'
    ];

    protected $searchable = [
        'pol_nome' => 'like'
    ];

    public function instituicao()
    {
        return $this->belongsTo('Modulos\Academico\Models\Instituicao', 'pol_itt_id');
    }

    public function ofertas_cursos()
    {
        return $this->belongsToMany('Modulos\Academico\Models\OfertaCurso', 'acd_polos_ofertas_cursos', 'poc_pol_id', 'poc_ofc_id');
    }

    public function grupos()
    {
        return $this->hasMany('Modulos\Academico\Models\Grupo', 'grp_pol_id');
    }
}
