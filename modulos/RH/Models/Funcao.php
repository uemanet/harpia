<?php


namespace Modulos\RH\Models;


use Modulos\Core\Model\BaseModel;

class Funcao extends BaseModel
{
    protected $table = 'reh_funcoes';

    protected  $primaryKey = 'fun_id';

    protected  $fillable = [
        'fun_descricao'
    ];

    protected  $searchable = [
        'fun_descricao' => 'like'
    ];
}