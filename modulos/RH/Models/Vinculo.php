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
}