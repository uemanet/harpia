<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Livro extends BaseModel
{
    protected $table = 'acd_livros';

    protected $primaryKey = 'liv_id';

    protected $fillable = [
        'liv_numero',
        'liv_tipo_livro'
    ];

    public function registros()
    {
        return $this->hasMany('Modulos\Academico\Models\Registro', 'reg_liv_id');
    }
}
