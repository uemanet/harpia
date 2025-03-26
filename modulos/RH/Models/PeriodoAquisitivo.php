<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class PeriodoAquisitivo extends BaseModel
{
    protected $table = 'reh_periodos_aquisitivos';

    protected $primaryKey = 'paq_id';

    protected $fillable = [
        'paq_id',
        'paq_mtc_id',
        'paq_col_id',
        'paq_data_inicio',
        'paq_data_fim',
        'paq_observacao',
        'paq_ferias_gozadas',
        'paq_gozo_inicio',
        'paq_gozo_fim',
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'paq_col_id', 'col_id');
    }

    public function periodos_gozo()
    {
        return $this->hasMany('Modulos\RH\Models\PeriodoGozo', 'pgz_paq_id', 'paq_id');
    }

    // Accessors
    public function getPaqDataInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setPaqDataInicioAttribute($value)
    {;
        $this->attributes['paq_data_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    // Accessors
    public function getPaqDataFimAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setPaqDataFimAttribute($value)
    {
        $this->attributes['paq_data_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    //ACESSOS E MUTATORS PERÍODO DE GOZO

    public function getPaqGozoInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setPaqGozoInicioAttribute($value)
    {
        $this->attributes['paq_gozo_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    // Accessors
    public function getPaqGozoFimAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setPaqGozoFimAttribute($value)
    {
        $this->attributes['paq_gozo_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }


}
