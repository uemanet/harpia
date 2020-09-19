<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;

class SeletivoMatricula extends BaseModel
{
    public $table = 'mat_seletivos_matriculas';
    protected $connection = 'mysql1';

    protected $fillable = [
        'seletivo_user_id',
        'chamada_id',
        'matriculado',
    ];

    protected $searchable = [
        'matriculado' => '=',
        'chamada_id' => '=',
        'nome' => 'like',
        'email' => '=',
        'cpf' => '='
    ];

    public function user()
    {
        return $this->belongsTo(SeletivoUser::class, 'seletivo_user_id');
    }

    public function chamada()
    {
        return $this->belongsTo(Chamada::class, 'chamada_id');
    }
}
