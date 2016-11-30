<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class PeriodoLetivo extends BaseModel
{
    protected $table = 'acd_periodos_letivos';

    protected $primaryKey = 'per_id';

    protected $fillable = [
        'per_nome',
        'per_inicio',
        'per_fim'
    ];

    protected $searchable = [
        'per_nome' => 'like'
    ];


    public function turmas()
    {
        return $this->hasMany('Modulos\Academico\Models\Turma', 'trm_per_id', 'per_id');
    }

    public function ofertasDisciplinas()
    {
        return $this->hasMany('Modulos\Academico\Models\OfertaDisciplina', 'ofd_per_id', 'per_id');
    }

    // Accessors
    public function getPerInicioAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    public function getPerFimAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    // Mutators
    public function setPerInicioAttribute($value)
    {
        $this->attributes['per_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function setPerFimAttribute($value)
    {
        $this->attributes['per_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}
