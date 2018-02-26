<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Disciplina extends BaseModel
{
    protected $table = 'acd_disciplinas';

    protected $primaryKey = 'dis_id';

    protected $fillable = [
        'dis_nvc_id',
        'dis_nome',
        'dis_carga_horaria',
        'dis_bibliografia',
        'dis_creditos',
        'dis_ementa'
    ];

    protected $searchable = [
        'dis_nvc_id' => '=',
        'dis_nome' => 'like'
    ];

    public function nivel()
    {
        return $this->belongsTo('Modulos\Academico\Models\NivelCurso', 'dis_nvc_id', 'nvc_id');
    }

    public function modulos()
    {
        return $this->belongsToMany('Modulos\Academico\Models\ModuloMatriz', 'acd_modulos_disciplinas', 'mdc_dis_id', 'mdc_dis_id');
    }
}
