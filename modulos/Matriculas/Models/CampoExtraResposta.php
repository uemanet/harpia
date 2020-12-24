<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class CampoExtraResposta extends BaseModel
{
    protected $connection = 'mysql2';
    public $table = 'campos_extras_respostas';

    protected $fillable = [
        'user_id', 'campo_extra_id', 'resposta'
    ];
}
