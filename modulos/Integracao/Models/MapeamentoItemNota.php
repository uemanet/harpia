<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class MapeamentoItemNota extends BaseModel
{
    protected $table = 'int_mapeamento_itens_nota';

    protected $primaryKey = 'min_id';

    protected $fillable = [
        'min_ofd_id',
        'min_id_nota_um',
        'min_id_nota_dois',
        'min_id_nota_tres',
        'min_id_recuperacao',
        'min_id_conceito',
        'min_id_final'
    ];
}
