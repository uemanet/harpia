<?php

namespace Modulos\Academico\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modulos\Core\Model\BaseModel;

class Noticia extends BaseModel
{
    protected $table = 'acd_noticias';

    protected $primaryKey = 'not_id';

    protected $fillable = [
        'not_pes_id', 'not_titulo', 'not_descricao', 'not_corpo'
    ];

    protected $searchable = [
        'not_pes_id' => '=',
        'not_titulo' => 'like',
        'not_descricao' => 'like',
        'not_corpo' => 'like'
    ];
}
