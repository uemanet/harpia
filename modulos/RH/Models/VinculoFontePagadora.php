<?php


namespace Modulos\RH\Models;


use Modulos\Core\Model\BaseModel;

class VinculoFontePagadora extends BaseModel
{
    protected $table = 'reh_vinculos_fontes_pagadoras';

    protected $primaryKey = 'vfp_id';

    protected $fillable = [
        'vfp_vin_id',
        'vfp_fpg_id,',
        'vfp_unidade',
        'vfp_valor'
    ];

    protected $searchable = [
        'vin_descricao' => 'like'
    ];

    public function vinculo()
    {
        return $this->belongsTo('Modulos\RH\Models\Vinculo', 'vfp_vin_id');
    }

    public function fonte_pagadora()
    {
        return $this->belongsTo('Modulos\RH\Models\FontePagadora', 'vfp_fpg_id');
    }
}