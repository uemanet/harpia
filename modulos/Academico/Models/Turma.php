<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Turma extends BaseModel
{
    protected $table = 'acd_turmas';

    protected $primaryKey = 'trm_id';

    protected $fillable = [
        'trm_ofc_id',
        'trm_per_id',
        'trm_nome',
        'trm_qtd_vagas'
    ];

    protected $searchable = [
        'trm_nome' => 'like'
    ];

    public function oferta()
    {
        return $this->belongsTo('Modulos\Academico\Models\OfertaCurso', 'trm_ofc_id');
    }

    public function periodo()
    {
        return $this->belongsTo('Modulos\Academico\Models\PeriodoLetivo', 'trm_per_id');
    }

    public function grupos()
    {
        return $this->hasMany('Modulos\Academico\Models\Grupo', 'grp_trm_id');
    }
}
