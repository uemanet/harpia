<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class MenuItem extends BaseModel
{
    protected $table = 'seg_menu_itens';

    protected $primaryKey = 'mit_id';

    protected $fillable = [
        'mit_mod_id',
        'mit_item_pai',
        'mit_nome',
        'mit_icone',
        'mit_visivel',
        'mit_rota',
        'mit_descricao',
        'mit_ordem'
    ];

    protected $searchable = [
        'mit_mod_id' => '=',
        'mit_nome' => 'like'
    ];

    public function setMitVisivelAttribute($value)
    {
        $this->attributes['mit_visivel'] = (empty($value)) ? 0 : 1;
    }

    public function setMitRotaAttribute($value)
    {
        $this->attributes['mit_rota'] = (empty($value)) ? null : $value;
    }

    public function modulo()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\Modulo', 'mit_mod_id');
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
