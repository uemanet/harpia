<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class Seletivo extends BaseModel
{
    public $table = 'seletivos';
    protected $connection = 'mysql2';

    protected $fillable = [
        'nome',
        'descricao',
        'vagas',
        'inicio_inscricao',
        'fim_inscricao',
        'publicado',
        'data_divulgacao_resultado'
    ];

    protected $searchable = [
        'nome' => 'like',
        'descricao' => 'like'
    ];

    protected $casts = [
        'publicado' => 'boolean'
    ];

    public function chamadas()
    {
        return $this->hasMany(Chamada::class);
    }
}
