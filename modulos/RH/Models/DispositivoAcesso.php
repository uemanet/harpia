<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class DispositivoAcesso extends BaseModel
{
    protected $table = 'reh_dispositivos_acesso';

    protected $primaryKey = 'dis_id';

    protected $fillable = [
        'dis_nome',
        'dis_identificador',
        'dis_tipo',
        'dis_ip',
        'dis_modelo',
        'dis_token_api',
        'dis_status',
        'dis_ultimo_access_log_id',
        'dis_ultima_coleta_em',
        'dis_observacao',
    ];

    protected $searchable = [
        'dis_nome' => 'like',
        'dis_identificador' => 'like',
        'dis_tipo' => '=',
        'dis_ip' => 'like',
        'dis_status' => '=',
    ];

    public function eventos_acesso()
    {
        return $this->hasMany('Modulos\RH\Models\EventoAcesso', 'eva_dis_id', 'dis_id');
    }

    public function mapeamentos()
    {
        return $this->hasMany('Modulos\RH\Models\MapeamentoDispositivo', 'map_dis_id', 'dis_id');
    }
}
