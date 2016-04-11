<?php

namespace App\Models\Security;

use App\Models\BaseModel;


class Perfil extends BaseModel
{
    protected $table = 'seg_perfis';

    protected $primaryKey = 'prf_id';

    protected $fillable = ['prf_nome', 'prf_descricao', 'prf_mod_id'];


    /**
     * Relacionamento NxN com a tabela de permissoes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    
    public function permissoes()
    {
        return $this->belongsToMany('App\Models\Security\Permissao', 'seg_perfis_permissoes', 'prp_prf_id', 'prp_prm_id');
    }

    public function modulo()
    {
        return $this->belongsTo('App\Models\Security\Modulo', 'prf_mod_id', 'mod_id');
    }
}