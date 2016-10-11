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
        'mdo_mtc_id'
    ];

    protected $searchable = [
        'mdo_nome' => 'like'
    ];

    public function matriz()
    {
        return $this->belongsTo('Modulos\Academico\Models\MatrizCurricular', 'mdo_mtc_id', 'mtc_id');
    }

}
