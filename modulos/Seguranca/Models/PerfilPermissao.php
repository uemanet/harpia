<?php

namespace Modulos\Seguranca\Models;

use App\Models\BaseModel;

class PerfilPermissao extends BaseModel {
    protected $table = 'seg_perfis_permissoes';

    protected $primaryKey = 'teste';

    protected $fillable = ['prp_prf_id', 'prp_prm_id'];
}
