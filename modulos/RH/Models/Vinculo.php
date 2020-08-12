<?php


namespace Modulos\RH\Models;


use Modulos\Core\Model\BaseModel;

class Vinculo extends  BaseModel
{
    protected $table = 'reh_vinculos';

    protected  $primaryKey = 'vin_id';

    protected  $fillable = [
      'vin_descricao'
    ];

    protected  $searchable = [
      'vin_descricao' => 'like'
    ];

    public function vinculos_fontes_pagadoras()
    {
        return $this->hasMany('Modulos\RH\Models\VinculoFontePagadora', 'vfp_vin_id', 'vin_id');
    }

}