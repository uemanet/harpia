<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class PeriodoGozo extends BaseModel
{
    protected $table = 'reh_horas_periodos_gozo';

    protected $primaryKey = 'pgz_id';


    protected $fillable = [
        'pgz_paq_id',
        'pgz_data_inicio',
        'pgz_data_fim',
        'pgz_observacao',
        'pgz_ferias_gozadas'
    ];


    // Mutators
    public function setPgzDataInicioAttribute($value)
    {;
        $this->attributes['pgz_data_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    // Mutators
    public function setPgzDataFimAttribute($value)
    {
        $this->attributes['pgz_data_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function getPgzDataInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }
    public function getPgzDataFimAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    public function periodoAquisitivo()
    {
        return $this->belongsTo('Modulos\RH\Models\PeriodoAquisitivo', 'pgz_paq_id', 'paq_id');
    }


}
