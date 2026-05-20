<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class JornadaRemota extends BaseModel
{
    protected $table = 'reh_jornadas_remotas';

    protected $primaryKey = 'jor_id';

    protected $fillable = [
        'jor_col_id',
        'jor_eva_entrada_id',
        'jor_eva_saida_id',
        'jor_data_referencia',
        'jor_entrada_em',
        'jor_saida_em',
        'jor_atividades',
        'jor_horas_calculadas',
        'jor_horas_aprovadas',
        'jor_status',
        'jor_motivo_aprovacao',
        'jor_aprovador_col_id',
        'jor_aprovado_em',
    ];

    protected $searchable = [
        'jor_status' => '=',
        'jor_col_id' => '=',
        'jor_data_referencia' => '=',
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'jor_col_id', 'col_id');
    }

    public function eventoEntrada()
    {
        return $this->belongsTo('Modulos\RH\Models\EventoAcesso', 'jor_eva_entrada_id', 'eva_id');
    }

    public function eventoSaida()
    {
        return $this->belongsTo('Modulos\RH\Models\EventoAcesso', 'jor_eva_saida_id', 'eva_id');
    }

    public function aprovador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'jor_aprovador_col_id', 'col_id');
    }

    public function aprovacoes()
    {
        return $this->hasMany('Modulos\RH\Models\AprovacaoPonto', 'apr_jor_id', 'jor_id');
    }
}
