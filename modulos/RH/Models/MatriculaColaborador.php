<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class MatriculaColaborador extends BaseModel
{
    protected $table = 'reh_matriculas';

    protected $primaryKey = 'mtc_id';

    protected $fillable = [
        'mtc_col_id',
        'mtc_data_inicio',
        'mtc_data_fim',
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'mtc_col_id', 'col_id');
    }

    // Accessors
    public function getMtcDataInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setMtcDataInicioAttribute($value)
    {
        $this->attributes['mtc_data_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    // Accessors
    public function getMtcDataFimAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setMtcDataFimAttribute($value)
    {
        $this->attributes['mtc_data_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}
