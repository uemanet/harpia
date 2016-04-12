<?php

namespace Modulos\Seguranca\Models;

use App\Models\BaseModel;

class PerfilUsuario extends BaseModel {
    protected $table = 'seg_perfis_usuarios';

    protected $fillable = ['pru_prf_id', 'pru_usr_id'];
}
