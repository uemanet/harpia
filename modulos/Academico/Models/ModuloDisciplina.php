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
}
