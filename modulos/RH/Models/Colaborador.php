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
        'col_qtd_filho',
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
        'cfn_set_id' => 'like',
        'col_status' => 'like',
        'funcoes' => 'like',

    ];

    public function atividades_extras()
    {
        return $this->hasMany('Modulos\RH\Models\AtividadeExtraColaborador', 'atc_col_id', 'col_id');
    }

    public function horas_diarias()
    {
        return $this->hasMany('Modulos\RH\Models\HoraTrabalhadaDiaria', 'htd_col_id', 'col_id');
    }

    public function contas_colaboradores()
    {
        return $this->hasMany('Modulos\RH\Models\ContaColaborador', 'ccb_col_id', 'col_id');
    }

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'col_pes_id');
    }

    public function funcoes()
    {
        return $this->hasMany('Modulos\RH\Models\ColaboradorFuncao', 'cfn_col_id', 'col_id')
            ->where('cfn_data_fim', null);
    }

    public function periodos_aquisitivos()
    {
        return $this->hasMany('Modulos\RH\Models\PeriodoAquisitivo', 'paq_col_id', 'col_id');
    }

    public function matriculas()
    {
        return $this->hasMany('Modulos\RH\Models\MatriculaColaborador', 'mtc_col_id', 'col_id');
    }

    public function funcoes_historico()
    {

        return $this->hasMany('Modulos\RH\Models\ColaboradorFuncao', 'cfn_col_id', 'col_id')
            ->where('cfn_data_fim', '<>',null);
    }
}