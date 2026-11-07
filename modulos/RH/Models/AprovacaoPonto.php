<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class AprovacaoPonto extends BaseModel
{
    protected $table = 'reh_aprovacoes_ponto';

    protected $primaryKey = 'apr_id';

    protected $fillable = [
        'apr_eva_id',
        'apr_jor_id',
        'apr_aprovador_col_id',
        'apr_data_aprovacao',
        'apr_status',
        'apr_motivo',
        'apr_hora_ajustada',
        'apr_horas_aceitas',
    ];

    protected $searchable = [
        'apr_status' => '=',
    ];

    public function evento()
    {
        return $this->belongsTo('Modulos\RH\Models\EventoAcesso', 'apr_eva_id', 'eva_id');
    }

    public function aprovador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'apr_aprovador_col_id', 'col_id');
    }

    public function jornada()
    {
        return $this->belongsTo('Modulos\RH\Models\JornadaRemota', 'apr_jor_id', 'jor_id');
    }
}
