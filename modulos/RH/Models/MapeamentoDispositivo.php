<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class MapeamentoDispositivo extends BaseModel
{
    protected $table = 'reh_mapeamento_dispositivo';

    protected $primaryKey = 'map_id';

    protected $fillable = [
        'map_dis_id',
        'map_col_id',
        'map_user_id',
        'map_registration',
        'map_nome_dispositivo',
        'map_ativo',
    ];

    protected $searchable = [
        'map_user_id' => 'like',
        'map_registration' => 'like',
        'map_ativo' => '=',
    ];

    public function dispositivo()
    {
        return $this->belongsTo('Modulos\RH\Models\DispositivoAcesso', 'map_dis_id', 'dis_id');
    }

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'map_col_id', 'col_id');
    }
}
