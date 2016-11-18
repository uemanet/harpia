<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class AmbienteServico extends BaseModel
{
    protected $table = 'int_ambientes_servicos';

    protected $primaryKey = 'asr_id';

    protected $fillable = [
        'asr_amb_id',
        'asr_ser_id',
        'asr_token'
    ];

    protected $searchable = [
        'asr_token' => 'like'
    ];

    public function servico()
    {
        return $this->belongsTo('Modulos\Integracao\Models\Servico', 'asr_ser_id', 'ser_id');
    }
}
