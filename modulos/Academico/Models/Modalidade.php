<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Modalidade extends BaseModel
{
    protected $table = 'acd_modalidades';

    protected $primaryKey = 'mdl_id';

    protected $fillable = [
        'mdl_nome'
    ];

    protected $searchable = [
        'mdl_nome' => 'like',
    ];


    public function ofertas()
    {
        return $this->hasMany('Modulos\Academico\Models\OfertaCurso', 'ofc_mdl_id', 'mdl_id');
    }
}
