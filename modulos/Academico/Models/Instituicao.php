<?php

namespace Modulos\Academico\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modulos\Core\Model\BaseModel;

class Instituicao extends BaseModel
{
    protected $table = 'acd_instituicoes';

    protected $primaryKey = 'itt_id';

    protected $fillable = [
        'itt_nome'
    ];

    protected $searchable = [
        'itt_nome' => 'like'
    ];
}
