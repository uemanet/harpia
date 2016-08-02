<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Pessoa extends BaseModel
{
    protected $table = 'gra_pessoas';

    protected $primaryKey = 'pes_id';

    protected $fillable = [
        'pes_nome',
        'pes_sexo',
        'pes_email',
        'pes_telefone',
        'pes_nascimento',
        'pes_mae',
        'pes_pai',
        'pes_estado_civil',
        'pes_naturalidade',
        'pes_nacionalidade',
        'pes_raca',
        'pes_necessidade_especial',
        'pes_estrangeiro'
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => '=',
        'pes_cpf' => '='
    ];
}