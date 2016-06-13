<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class Recurso extends BaseModel
{

    protected $table = 'seg_recursos';

    protected $primaryKey = 'rcs_id';

    protected $fillable = ['rcs_ctr_id', 'rcs_nome', 'rcs_descricao', 'rcs_icone', 'rcs_ativo', 'rcs_ordem'];

    protected $searchable = [
        'rcs_nome' => 'like'
    ];

    public function categoria()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\CategoriaRecurso', 'rcs_ctr_id', 'ctr_id');
    }
}
