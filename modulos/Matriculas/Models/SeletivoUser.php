<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;

class SeletivoUser extends BaseModel
{
    public $table = 'seletivos_users';
    protected $connection = 'mysql1';

    protected $fillable = [
        'nome',
        'rg',
        'cpf',
        'estrangeiro',
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
        'endereco',
        'numero',
        'celular',
        'telefone',
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
    ];
}
