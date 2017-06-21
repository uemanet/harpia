<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Diploma extends BaseModel
{
    protected $table = 'acd_certificados';

    protected $primaryKey = 'dip_id';

    protected $fillable = [
        'dip_reg_id',
        'dip_mat_id',
        'dip_processo',
        'dip_codigo_autenticidade_externo'
    ];

    public function registro()
    {
        return $this->belongsTo('Modulos\Academico\Models\Registro', 'dip_reg_id');
    }
}
