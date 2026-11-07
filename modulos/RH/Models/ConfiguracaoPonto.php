<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class ConfiguracaoPonto extends BaseModel
{
    protected $table = 'reh_configuracoes_ponto';

    protected $primaryKey = 'cop_id';

    protected $fillable = [
        'cop_chave',
        'cop_valor',
        'cop_descricao',
    ];

    protected $searchable = [
        'cop_chave' => 'like',
        'cop_descricao' => 'like',
    ];
}
