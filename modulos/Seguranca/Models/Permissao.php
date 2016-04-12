<?php

namespace Modulos\Seguranca\Models;

use App\Models\BaseModel;

class Permissao extends BaseModel
{
    protected $table = 'seg_permissoes';

    protected $primaryKey = 'prm_id';

    protected $fillable = ['prm_nome', 'prm_descricao', 'prm_rcs_id'];
	
	public function recurso()
    {
        return $this->belongsTo('App\Models\Security\Recurso', 'prm_rcs_id', 'rcs_id');
    }
}
