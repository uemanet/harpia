<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class Sincronizacao extends BaseModel
{
    protected $table = 'int_sync_moodle';

    protected $primaryKey = 'sym_id';

    protected $fillable = [
        'sym_table',
        'sym_table_id',
        'sym_action',
        'sym_status',
        'sym_mensagem',
        'sym_data_envio',
        'sym_extra',
        'sym_version'
    ];

    protected $searchable = [
        'sym_table' => 'like',
        'sym_table_id' => '=',
        'sym_status' => '=',
        'sym_data_envio' => '='
    ];
}
