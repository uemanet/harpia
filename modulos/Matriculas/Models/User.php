<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;

class User extends BaseModel
{
    public $table = 'users';
    protected $connection = 'mysql2';

    protected $fillable = [
        'nome',
        'rg',
        'cpf',
        'estrangeiro',
        'documento_estrangeiro',
        'nascimento',
        'sexo',
        'estado_civil',
        'mae' ,
        'pai',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'email',
        'perfil',
        'endereco',
        'numero',
        'celular',
        'telefone',
        'graduacao_id',
        'graduacao_instituicao',
        'graduacao_instituicao_sigla',
        'graduacao_ano',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $searchable = [
        'nome' => 'like',
        'email' => '=',
        'cpf' => '=',
        'perfil' => '='
    ];
}
