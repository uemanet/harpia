<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Aluno extends BaseModel
{
    protected $table = 'acd_alunos';

    protected $primaryKey = 'alu_id';

    protected $fillable = ['alu_pes_id'];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '='
    ];

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'alu_pes_id');
    }
}
