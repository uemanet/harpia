<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class Colaborador extends BaseModel
{
    protected $table = 'reh_colaboradores';

    protected $primaryKey = 'col_id';

    protected $fillable = [
        'col_pes_id',
        'col_set_id',
        'col_fun_id',
        'col_qtd_filho',
        'col_data_admissao',
        'col_ch_diaria',
        'col_codigo_catraca',
        'col_vinculo_universidade',
        'col_matricula_universidade',
        'col_observacao',
        'col_status',
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '=',
        'col_set_id' => '=',
        'col_status' => 'like',
    ];

    public function atividades_extras()
    {
        return $this->hasMany('Modulos\RH\Models\AtividadeExtraColaborador', 'atc_col_id', 'col_id');
    }

    public function contas_colaboradores()
    {
        return $this->hasMany('Modulos\RH\Models\ContaColaborador', 'ccb_col_id', 'col_id');
    }

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'col_pes_id');
    }

    public function setor()
    {
        return $this->belongsTo('Modulos\RH\Models\Setor', 'col_set_id');
    }

    public function funcao()
    {
        return $this->belongsTo('Modulos\RH\Models\Funcao', 'col_fun_id');
    }

    public function funcoes()
    {
        return $this->belongsToMany('Modulos\RH\Models\Funcao', 'reh_colaboradores_funcoes', 'cfn_col_id', 'cfn_fun_id')->wherePivot('cfn_data_fim', null)->withPivot('cfn_id');
    }

    public function funcoes_historico()
    {
        return $this->belongsToMany('Modulos\RH\Models\Funcao', 'reh_colaboradores_funcoes', 'cfn_col_id', 'cfn_fun_id')
            ->wherePivot('cfn_data_fim', '<>',null)
            ->withPivot(['cfn_id', 'cfn_data_inicio', 'cfn_data_fim']);
    }

    // Accessors
    public function getColDataAdmissaoAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setColDataAdmissaoAttribute($value)
    {
        $this->attributes['col_data_admissao'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

}