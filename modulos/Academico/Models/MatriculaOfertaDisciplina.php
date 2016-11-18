<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class MatriculaOfertaDisciplina extends BaseModel
{
    protected $table = 'acd_matriculas_ofertas_disciplinas';

    protected $primaryKey = 'mof_id';

    protected $fillable = [
        'mdc_dis_id',
        'mdc_mdo_id',
        'mdc_tipo_avaliacao'
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
}
