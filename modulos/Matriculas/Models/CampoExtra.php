<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class CampoExtra extends BaseModel
{
    protected $connection = 'mysql2';
    public $table = 'campos_extras';

    protected $fillable = [
        'seletivo_id', 'nome'
    ];

}
