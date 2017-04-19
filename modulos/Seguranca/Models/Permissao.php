<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Permissao extends BaseModel
{
    protected $table = 'seg_permissoes';

    protected $primaryKey = 'prm_id';

    protected $fillable = ['prm_nome', 'prm_rota'];

    protected $searchable = [
        'prm_nome' => 'like'
    ];

    public function perfis()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Perfil', 'seg_permissoes_perfis', 'prp_prm_id', 'prp_prf_id');
    }
}
