<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;

class SeletivoMatricula extends BaseModel
{
    public $table = 'seletivos_matriculas';
    protected $connection = 'mysql1';

    protected $fillable = [
        'seletivo_user_id',
        'chamada_id',
        'matriculado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'seletivo_user_id');
    }

    public function chamadas()
    {
        return $this->hasMany(Chamada::class);
    }
}
