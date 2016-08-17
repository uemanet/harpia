<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Tutor extends BaseModel
{
    protected $table = 'acd_tutor';

    protected $primaryKey = 'tut_id';

    protected $fillable = [
        'tut_prf_id'
    ];

    protected $searchable = [
        'tut_prf_id' => '='
    ];

    public function professor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Professor', 'tut_prf_id');
    }
}
