<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Modulo extends BaseModel
{
    protected $table = 'seg_modulos';

    protected $primaryKey = 'mod_id';

    protected $fillable = ['mod_nome', 'mod_slug', 'mod_icone'];

    protected $searchable = [
        'mod_nome' => 'like'
    ];

    public function perfis()
    {
        return $this->hasMany('Modulos\Seguranca\Models\Perfil', 'prf_mod_id');
    }

    public function menu_itens()
    {
        return $this->hasMany('Modulos\Seguranca\Models\MenuItem', 'mit_mod_id');
    }
}
