<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Perfil extends BaseModel
{
    protected $table = 'seg_perfis';

    protected $primaryKey = 'prf_id';

    protected $fillable = [
        'prf_mod_id',
        'prf_nome',
        'prf_descricao'
    ];

    protected $searchable = [
        'prf_nome' => 'like'
    ];
    
    public function permissoes()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Permissao', 'seg_permissoes_perfis', 'prp_prf_id', 'prp_prm_id');
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
