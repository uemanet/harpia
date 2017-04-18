<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Registro extends BaseModel
{
    protected $table = 'acd_registros';

    protected $primaryKey = 'reg_id';

    protected $fillable = [
        'reg_liv_id',
        'reg_mat_id',
        'reg_folha',
        'reg_registro',
        'reg_registro_externo',
        'reg_processo',
        'reg_data_expedicao',
        'reg_observacao',
        'reg_usuario',
        'reg_data',
        'reg_id_interno',
        'reg_mdo_id'
    ];

    public function livro()
    {
        return $this->belongsTo('Modulos\Academico\Models\Livro', 'reg_liv_id');
    }
}
