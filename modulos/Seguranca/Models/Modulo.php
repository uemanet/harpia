<?php

namespace App\Models\Security;

use App\Models\BaseModel;


class Modulo extends BaseModel {

    protected $table = 'seg_modulos';

    protected $primaryKey = 'mod_id';

    protected $fillable = ['mod_nome', 'mod_descricao', 'mod_icone','mod_style','mod_ativo'];
}