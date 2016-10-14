<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class NivelCurso extends BaseModel
{
    protected $table = 'acd_niveis_cursos';

    protected $primaryKey = 'nvc_id';

    protected $fillable = [
        'nvc_nome'
    ];

    protected $searchable = [
        'nvc_nome' => 'like',
    ];


    public function cursos()
    {
        return $this->hasMany('Modulos\Academico\Models\Curso', 'crs_nvc_id', 'nvc_id');
    }

    public function disciplinas()
    {
        return $this->hasMany('Modulos\Academico\Models\Disciplina', 'dis_nvc_id', 'nvc_id');
    }
}
