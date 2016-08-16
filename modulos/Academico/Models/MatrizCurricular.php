<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class MatrizCurricular extends BaseModel
{
    protected $table = 'acd_matrizes_curriculares';

    protected $primaryKey = 'mtc_id';

    protected $fillable = [
        'mtc_crs_id', 'mtc_anx_projeto_pedagogico', 'mtc_descricao',
        'mtc_data', 'mtc_creditos', 'mtc_horas', 'mtc_horas_praticas'
    ];

    protected $searchable = [
        'per_inicio' => 'like',
    ];

    public function curso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Curso', 'mtc_crs_id');
    }
}
