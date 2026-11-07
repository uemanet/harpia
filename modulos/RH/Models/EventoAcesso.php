<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class EventoAcesso extends BaseModel
{
    protected $table = 'reh_eventos_acesso';

    protected $primaryKey = 'eva_id';

    protected $fillable = [
        'eva_col_id',
        'eva_dis_id',
        'eva_tipo',
        'eva_data_hora',
        'eva_origem',
        'eva_status',
        'eva_status_mensagem',
        'eva_hash',
        'eva_ip_origem',
        'eva_user_agent',
        'eva_observacao',
    ];

    protected $searchable = [
        'eva_tipo' => '=',
        'eva_origem' => '=',
        'eva_status' => '=',
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'eva_col_id', 'col_id');
    }

    public function dispositivo()
    {
        return $this->belongsTo('Modulos\RH\Models\DispositivoAcesso', 'eva_dis_id', 'dis_id');
    }

    public function aprovacoes()
    {
        return $this->hasMany('Modulos\RH\Models\AprovacaoPonto', 'apr_eva_id', 'eva_id');
    }

    public function jornada_entrada()
    {
        return $this->hasOne('Modulos\RH\Models\JornadaRemota', 'jor_eva_entrada_id', 'eva_id');
    }

    public function jornada_saida()
    {
        return $this->hasOne('Modulos\RH\Models\JornadaRemota', 'jor_eva_saida_id', 'eva_id');
    }
}
