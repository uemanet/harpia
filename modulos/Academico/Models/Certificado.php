<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Certificado extends BaseModel
{
    protected $table = 'acd_certificados';

    protected $primaryKey = 'crt_id';

    protected $fillable = [
        'crt_reg_id',
        'crt_mat_id',
        'crt_mdo_id'
    ];

    public function registro()
    {
        return $this->belongsTo('Modulos\Academico\Models\Registro', 'crt_reg_id');
    }
}
