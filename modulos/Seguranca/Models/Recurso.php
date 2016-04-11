<?php

namespace App\Models\Security;

use App\Models\BaseModel;

class Recurso extends BaseModel {

    protected $table = 'seg_recursos';

    protected $primaryKey = 'rcs_id';

    protected $fillable = ['rcs_mod_id', 'rcs_ctr_id', 'rcs_nome', 'rcs_descricao', 'rcs_icone', 'rcs_ativo', 'rcs_ordem'];

	public function modulo()
    {
        return $this->belongsTo('App\Models\Security\Modulo', 'rcs_mod_id', 'mod_id');
    }

	public function categoria()
    {
        return $this->belongsTo('App\Models\Security\CategoriaRecurso', 'rcs_ctr_id', 'ctr_id');
    }
}
