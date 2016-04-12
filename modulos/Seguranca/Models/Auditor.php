<?php

namespace Modulos\Seguranca\Models;

use Illuminate\Database\Eloquent\Model;

class Auditor extends Model {

    protected $table = 'seg_auditores';

    protected $primaryKey = 'log_id';

    protected $fillable = ['log_usr_nome', 'log_model', 'log_model_id', 'log_type', 'log_data'];

}
