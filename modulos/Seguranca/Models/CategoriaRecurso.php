<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;

class CategoriaRecurso extends BaseModel {
    protected $table = 'seg_categorias_recursos';

    protected $primaryKey = 'ctr_id';

    protected $fillable = ['ctr_mod_id', 'ctr_nome', 'ctr_descricao', 'ctr_icone', 'ctr_ordem', 'ctr_ativo', 'ctr_visivel', 'ctr_referencia'];

    protected $searchable = [
        'ctr_nome' => 'like'
    ];

    public function modulo()
    {
        return $this->belongsTo('Modulos\Seguranca\Models\Modulo', 'prf_mod_id', 'mod_id');
    }
}