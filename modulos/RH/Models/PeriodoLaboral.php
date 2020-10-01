<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class PeriodoLaboral extends BaseModel
{
    protected $table = 'reh_periodos_laborais';

    protected $primaryKey = 'pel_id';

    protected $fillable = [
        'pel_inicio',
        'pel_termino',
        'pel_encerramento'
    ];

    protected $searchable = [
        'pel_inicio' => 'like'
    ];

    // Accessors
    public function getPelInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setPelInicioAttribute($value)
    {
        $this->attributes['pel_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    // Accessors
    public function getPelTerminoAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setPelTerminoAttribute($value)
    {
        $this->attributes['pel_termino'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

}
