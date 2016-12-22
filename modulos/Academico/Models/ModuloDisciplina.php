<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class ModuloDisciplina extends BaseModel
{
    protected $table = 'acd_modulos_disciplinas';

    protected $primaryKey = 'mdc_id';

    protected $fillable = [
        'mdc_dis_id',
        'mdc_mdo_id',
        'mdc_tipo_avaliacao',
        'mdc_tipo_disciplina',
        'mdc_pre_requisitos'
    ];

    protected $searchable = [
        'mdc_dis_id' => 'like'
    ];

    public function disciplina()
    {
        return $this->belongsTo('Modulos\Academico\Models\Disciplina', 'mdc_dis_id', 'dis_id');
    }

    public function modulo()
    {
        return $this->belongsTo('Modulos\Academico\Models\ModuloMatriz', 'mdc_mdo_id', 'mdo_id');
    }

    public function ofertasDisciplinas()
    {
        return $this->hasMany('Modulos\Academico\Models\OfertaDisciplina', 'ofd_mdc_id', 'mdc_id');
    }

    public function getMdcTipoDisciplinaAttribute($value)
    {
        $values = [
            'obrigatoria' => 'Obrigatória',
            'optativa' => 'Optativa',
            'eletiva' => 'Eletiva',
            'tcc' => 'TCC'
        ];

        return $values[$value];
    }

    public function getMdcTipoAvaliacaoAttribute($value)
    {
        $values = [
            'numerica' => 'Numérica',
            'conceito' => 'Conceito'
        ];

        return $values[$value];
    }
}
