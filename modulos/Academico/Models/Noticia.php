<?php

namespace Modulos\Academico\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modulos\Core\Model\BaseModel;

class Noticia extends BaseModel
{
    protected $table = 'acd_noticias';

    protected $primaryKey = 'ntc_id';

    protected $fillable = [
        'ntc_titulo', 'ntc_descricao'
    ];

    protected $searchable = [
        'ntc_titulo' => 'like',
        'ntc_descricao' => 'like'
    ];
}
