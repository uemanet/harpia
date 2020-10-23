<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class Inscricao extends BaseModel
{
    protected $connection = 'mysql2';
    public $table = 'inscricoes';

    protected $fillable = [
        'seletivo_id', 'user_id', 'status', 'pontuacao', 'extras'
    ];

    protected $searchable = [
        'status' => '=',
        'seletivo_id' => '=',
        'nome' => 'like',
        'email' => '=',
        'cpf' => '='
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seletivo()
    {
        return $this->belongsTo(Seletivo::class);
    }
}
