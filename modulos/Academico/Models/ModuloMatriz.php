<?php

namespace Modulos\Academico\Models;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Model\BaseModel;

class ModuloMatriz extends BaseModel
{
    protected $table = 'acd_modulos_matrizes';

    protected $primaryKey = 'mdo_id';

    protected $fillable = [
        'mdo_nome',
        'mdo_descricao',
        'mdo_qualificacao',
        'mdo_mtc_id',
        'mdo_cargahoraria_min_eletivas',
        'mdo_creditos_min_eletivas'
    ];

    protected $searchable = [
        'mdo_nome' => 'like'
    ];

    public function matriz()
    {
        return $this->belongsTo('Modulos\Academico\Models\MatrizCurricular', 'mdo_mtc_id', 'mtc_id');
    }

    public function disciplinas()
    {
        return $this->belongsToMany('Modulos\Academico\Models\Disciplina', 'acd_modulos_disciplinas', 'mdc_mdo_id', 'mdc_dis_id');
    }
}
