<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class HoraTrabalhadaDiaria extends BaseModel
{
    protected $table = 'reh_horas_trabalhadas_diarias';

    protected $primaryKey = 'htd_id';

    protected $fillable = [
        'htd_col_id',
        'htd_horas',
        'htd_data',
    ];

    protected $searchable = [
        'htd_col_id' => '=',
        'pel_inicio' => '=',
        'pel_termino' => '='
    ];

    public function colaborador()
    {
        return $this->hasOne('Modulos\RH\Models\Colaborador', 'col_id','htd_col_id');
    }

}
