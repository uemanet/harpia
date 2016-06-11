<?php

namespace Modulos\Geral\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {

    protected $table = 'gra_pessoa';

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
}