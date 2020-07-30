<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class AreaConhecimento extends BaseModel
{
    protected $table = 'reh_areas_conhecimentos';

    protected $primaryKey = 'arc_id';

    protected $fillable = [
        'arc_descricao'
    ];

    protected $searchable = [
        'arc_descricao' => 'like'
    ];

}
