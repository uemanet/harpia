<?php


namespace Modulos\RH\Models;


use Modulos\Core\Model\BaseModel;

class FontePagadora extends BaseModel
{
    protected $table = 'reh_fontes_pagadoras';

    protected $primaryKey = 'fpg_id';

    protected $fillable = [
        'fpg_razao_social',
        'fpg_nome_fantasia',
        'fpg_cnpj',
        'fpg_cep',
        'fpg_endereco',
        'fpg_bairro',
        'fpg_numero',
        'fpg_complemento',
        'fpg_cidade',
        'fpg_uf',
        'fpg_email',
        'fpg_telefone',
        'fpg_celular',
        'fpg_observacao',
    ];

    protected $searchable = [
        'fpg_razao_social' => 'like'
    ];

    public function vinculos_fontes_pagadoras()
    {
        return $this->hasMany('Modulos\RH\Models\VinculoFontePagadora', 'vfp_fpg_id', 'fpg_id');
    }
}