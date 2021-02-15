<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class ColaboradorFuncao extends BaseModel
{
    protected $table = 'reh_colaboradores_funcoes';

    protected $primaryKey = 'cfn_id';

    protected $fillable = [
        'cfn_fun_id',
        'cfn_col_id',
        'cfn_set_id',
        'cfn_data_inicio',
        'cfn_data_fim'
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '='
    ];

    public function funcao()
    {
        return $this->belongsTo('Modulos\RH\Models\Funcao', 'cfn_fun_id');
    }

    public function setor()
    {
        return $this->belongsTo('Modulos\RH\Models\Setor', 'cfn_set_id');
    }

    public function colaborador()
    {
        return $this->belongsToMany('Modulos\RH\Models\Funcao', 'reh_colaboradores_funcoes', 'cfn_col_id', 'cfn_fun_id');
    }

    // Accessors
    public function getCfnDataInicioAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setCfnDataInicioAttribute($value)
    {
        $this->attributes['cfn_data_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    // Accessors
    public function getCfnDataFimAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setCfnDataFimAttribute($value)
    {
        $this->attributes['cfn_data_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

}