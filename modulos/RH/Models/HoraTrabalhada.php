<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class HoraTrabalhada extends BaseModel
{
    protected $table = 'reh_horas_trabalhadas';

    protected $primaryKey = 'htr_id';

    protected $fillable = [
        'htr_col_id',
        'htr_pel_id',
        'htr_horas_previstas',
        'htr_horas_trabalhadas',
        'htr_horas_justificadas',
        'htr_saldo'
    ];

    protected $searchable = [
        'htr_pel_id' => 'like',
        'htr_col_id' => '=',
        'cfn_set_id' => '='
    ];

    public function colaborador()
    {
        return $this->hasOne('Modulos\RH\Models\Colaborador', 'col_id','htr_col_id');
    }

    public function periodo()
    {
        return $this->hasOne('Modulos\RH\Models\PeriodoLaboral', 'pel_id','htr_pel_id');
    }

}
