<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class HistoricoMatricula extends BaseModel
{
    protected $table = 'acd_historico_matriculas';

    protected $primaryKey = 'hmt_id';

    protected $fillable = [
        'hmt_mat_id',
        'hmt_data',
        'hmt_tipo',
        'hmt_observacao'
    ];


    public function matricula()
    {
        return $this->belongsTo('Modulos\Academico\Models\Matricula', 'hmt_mat_id', 'mat_id');
    }

    public function getHmtTipoAttribute($value)
    {
        $tipos = [
            'mudanca_polo' => 'Mudança de Polo',
            'mudanca_grupo' => 'Mudança de Grupo',
            'alteracao_status' => 'Alteração de Status'
        ];

        if ($value) {
            return $tipos[$value];
        }
    }
}
