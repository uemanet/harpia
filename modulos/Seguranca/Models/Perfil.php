<?php

namespace Modulos\Seguranca\Models;

use App\Models\BaseModel;

class Perfil extends BaseModel
{
    protected $table = 'seg_perfis';

    protected $primaryKey = 'prf_id';

    protected $fillable = ['prf_nome', 'prf_descricao', 'prf_mod_id'];
    
    public function permissoes()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Permissao', 'seg_perfis_permissoes', 'prp_prf_id', 'prp_prm_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Usuario', 'seg_perfis_usuarios', 'pru_prf_id', 'pru_usr_id');
    }

    public function modulo()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\Modulo', 'prf_mod_id', 'mod_id');
    }
}