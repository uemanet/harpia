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
        'trm_itt_id',
        'trm_nome',
        'trm_qtd_vagas',
        'trm_integrada',
        'trm_tipo_integracao'
    ];

    protected $searchable = [
        'trm_nome' => 'like'
    ];

    public function ofertacurso()
    {
        return $this->belongsTo('Modulos\Academico\Models\OfertaCurso', 'trm_ofc_id');
    }

    public function ofertasDisciplina()
    {
        return $this->hasMany('Modulos\Academico\Models\OfertaDisciplina', 'ofd_trm_id', 'ofd_id');
    }

    public function periodo()
    {
        return $this->belongsTo('Modulos\Academico\Models\PeriodoLetivo', 'trm_per_id');
    }

    public function instituicao()
    {
        return $this->belongsTo('Modulos\Academico\Models\Instituicao', 'trm_itt_id');
    }


    public function grupos()
    {
        return $this->hasMany('Modulos\Academico\Models\Grupo', 'grp_trm_id');
    }

    public function matriculas()
    {
        return $this->hasMany('Modulos\Academico\Models\Matricula', 'mat_trm_id');
    }

    public function ambientes()
    {
        return $this->belongsToMany('Modulos\Integracao\Models\AmbienteVirtual', 'int_ambientes_turmas', 'atr_trm_id', 'atr_amb_id');
    }

    public function getTrmIntegradaStringAttribute()
    {
        if ($this->trm_integrada == 0) {
            return 'Não';
        }
        return 'Sim';
    }
}
