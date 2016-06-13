<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Permissao extends BaseModel
{
    protected $table = 'seg_permissoes';

    protected $primaryKey = 'prm_id';

    protected $fillable = ['prm_rcs_id', 'prm_nome', 'prm_descricao'];

    protected $searchable = [
        'prm_nome' => 'like'
    ];

    public function recurso()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\Recurso', 'prm_rcs_id', 'rcs_id');
    }

    public function perfis()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Perfil', 'seg_perfis_permissoes', 'prp_prm_id', 'prp_prf_id');
    }
}
