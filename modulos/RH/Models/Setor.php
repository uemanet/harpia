<?php


namespace Modulos\RH\Models;


use Modulos\Core\Model\BaseModel;

class Setor extends BaseModel
{
    protected $table = 'reh_setores';

    protected $primaryKey = 'set_id';

    protected $fillable = [
      'set_descricao',
      'set_sigla',
    ];

    protected $searchable = [
        'set_descricao' => 'like'
    ];
}