<?php

namespace Modulos\Seguranca\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'seg_auditoria';

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'log_usr_id',
        'log_action',
        'log_table',
        'log_table_id',
        'log_object'
    ];

    public function usuario()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\Usuario', 'log_usr_id', 'usr_id');
    }
}
