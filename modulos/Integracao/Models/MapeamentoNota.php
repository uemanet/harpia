<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class MapeamentoNota extends BaseModel
{
    protected $table = 'int_mapeamento_itens_nota';

    protected $primaryKey = 'min_id';

    protected $searchable = [
        'min_id_conceito'
    ];

    protected $fillable = [
        'min_ofd_id',
        'min_id_nota1',
        'min_id_nota2',
        'min_id_nota3',
        'min_id_recuperacao',
        'min_id_conceito',
        'min_id_final'
    ];

    public function ofertadisciplina()
    {
        return $this->belongsTo('Modulos\Academico\Models\OfertaDisciplina', 'min_ofd_id', 'ofd_id');
    }
}
