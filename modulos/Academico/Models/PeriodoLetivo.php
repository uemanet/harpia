<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class PeriodoLetivo extends BaseModel
{
    protected $table = 'acd_periodos_letivos';

    protected $primaryKey = 'per_id';

    protected $fillable = [
        'per_inicio',
        'per_fim'
    ];

    protected $searchable = [
        'per_inicio' => 'like',
    ];


    public function turmas()
    {
        return $this->hasMany('Modulos\Academico\Models\Turma', 'trm_per_id', 'per_id');
    }


    // Accessors
    public function getPerInicioAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        $date = Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        return $date;
    }

    public function getPerFimAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        $date = Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        return $date;
    }
}
