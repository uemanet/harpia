<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Tutor extends BaseModel
{
    protected $table = 'acd_tutores';

    protected $primaryKey = 'tut_id';

    protected $fillable = [
        'tut_pes_id'
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '='
    ];

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'tut_pes_id');
    }
}
