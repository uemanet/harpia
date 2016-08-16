<?php
namespace Modulos\Geral\Models;


use Modulos\Core\Model\BaseModel;

class Titulacao extends BaseModel
{

    protected $table = 'gra_titulacoes';

    protected $primaryKey = 'tit_id';

    protected $fillable = [
        'tit_nome',
        'tit_descricao',
        'tit_peso'
    ];
}