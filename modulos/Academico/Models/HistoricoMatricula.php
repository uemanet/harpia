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
}
