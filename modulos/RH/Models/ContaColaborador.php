<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class ContaColaborador extends BaseModel
{
    protected $table = 'reh_contas_colaboradores';

    protected $primaryKey = 'ccb_id';

    protected $fillable = [
        'ccb_col_id',
        'ccb_ban_id',
        'ccb_agencia',
        'ccb_conta',
        'ccb_variacao'
    ];

    protected $searchable = [
        'ccb_agencia' => 'like'
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'ccb_col_id');
    }

    public function banco()
    {
        return $this->belongsTo('Modulos\RH\Models\Banco', 'ccb_ban_id');
    }

    public function salarios_colaboradores()
    {
        return $this->hasMany('Modulos\RH\Models\SalarioColaborador', 'scb_ccb_id');
    }

}