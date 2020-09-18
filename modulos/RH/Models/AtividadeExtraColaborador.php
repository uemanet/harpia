<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class AtividadeExtraColaborador extends BaseModel
{
    protected $table = 'reh_atividades_extras_colaboradores';

    protected $primaryKey = 'atc_id';

    protected $fillable = [
        'atc_col_id',
        'atc_titulo',
        'atc_descricao',
        'atc_tipo',
        'atc_carga_horaria',
        'atc_data_inicio',
        'atc_data_fim'
    ];

    protected $searchable = [
        'atc_titulo' => 'like'
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'atc_col_id');
    }

    // Accessors
    public function getAtcDataInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setAtcDataInicioAttribute($value)
    {
        $this->attributes['atc_data_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }


    // Accessors
    public function getAtcDataFimAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setAtcDataFimAttribute($value)
    {
        $this->attributes['atc_data_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

}