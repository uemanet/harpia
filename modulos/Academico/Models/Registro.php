<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Registro extends BaseModel
{
    protected $table = 'acd_registros';

    protected $primaryKey = 'reg_id';

    protected $fillable = [
        'reg_liv_id',
        'reg_usr_id',
        'reg_folha',
        'reg_registro',
        'reg_codigo_autenticidade'
    ];

    public function livro()
    {
        return $this->belongsTo('Modulos\Academico\Models\Livro', 'reg_liv_id');
    }
}
