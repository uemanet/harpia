<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Modulo extends BaseModel
{
    protected $table = 'seg_modulos';

    protected $primaryKey = 'mod_id';

    protected $fillable = ['mod_rota','mod_nome','mod_descricao','mod_icone','mod_class','mod_ativo'];

    protected $searchable = [
        'mod_nome' => 'like'
    ];
}