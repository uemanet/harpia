<?php

namespace Modulos\Seguranca\Models;

use App\Models\BaseModel;

class CategoriaRecurso extends BaseModel {
    protected $table = 'seg_categorias_recursos';

    protected $primaryKey = 'ctr_id';

    protected $fillable = ['ctr_nome', 'ctr_icone', 'ctr_ordem', 'ctr_ativo'];
}