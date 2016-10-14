<?php

namespace modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Vinculo extends BaseModel
{
    protected $table = 'acd_usuarios_cursos';

    protected $primaryKey = 'ucr_id';

    protected $fillable = ['ucr_usr_id', 'ucr_crs_id'];

    protected $searchable = [
        'ucr_usr_id' => '=',
        'ucr_crs_id' => '=',
    ];

    public function pessoas()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Usuario', 'usr_id', 'ucr_usr_id');
    }

    public function cursos()
    {
        return $this->belongsToMany('Modulos\Academico\Models\Curso', 'crs_id', 'ucr_crs_id');
    }
}
