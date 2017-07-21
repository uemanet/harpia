<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class ConfiguracaoCurso extends BaseModel
{
    protected $table = 'acd_configuracoes_cursos';

    protected $primaryKey = 'cfc_id';

    protected $fillable = [
        'cfc_crs_id',
        'cfc_nome',
        'cfc_valor'
    ];

    public function curso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Curso', 'cfc_crs_id', 'crs_id');
    }
}
