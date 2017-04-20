<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class MenuItem extends BaseModel
{
    protected $table = 'seg_menu_itens';

    protected $primaryKey = 'mit_id';

    protected $fillable = ['mit_mod_id', 'mit_nome', 'mit_icone'];

    public function modulo()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\Modulo', 'mod_id');
    }

    public function pai()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\MenuItem', 'mit_item_pai');
    }

    public function filhos()
    {
        return $this->hasMany('Modulos\Seguranca\Models\MenuItem', 'mit_item_pai');
    }
}
