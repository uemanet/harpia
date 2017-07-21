<?php

namespace Modulos\Academico\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class Matricula extends BaseModel
{
    protected $table = 'acd_matriculas';

    protected $primaryKey = 'mat_id';

    protected $fillable = [
        'mat_alu_id',
        'mat_trm_id',
        'mat_pol_id',
        'mat_grp_id',
        'mat_situacao',
        'mat_modo_entrada',
        'mat_data_conclusao'
    ];

    public function aluno()
    {
        return $this->belongsTo('Modulos\Academico\Models\Aluno', 'mat_alu_id');
    }

    public function turma()
    {
        return $this->belongsTo('Modulos\Academico\Models\Turma', 'mat_trm_id');
    }

    public function polo()
    {
        return $this->belongsTo('Modulos\Academico\Models\Polo', 'mat_pol_id');
    }

    public function grupo()
    {
        return $this->belongsTo('Modulos\Academico\Models\Grupo', 'mat_grp_id');
    }

    public function matriculasOfertasDisciplinas()
    {
        return $this->hasMany('Modulos\Academico\Models\MatriculaOfertaDisciplina', 'mof_mat_id', 'mat_id');
    }

    public function historico()
    {
        return $this->hasMany('Modulos\Academico\Models\HistoricoMatricula', 'hmt_mat_id', 'mat_id');
    }

    public function getMatModoEntradaAttribute($value)
    {
        $values = [
            'vestibular' => 'Vestibular',
            'transferencia_externa' => 'Transferência Externa',
            'transferencia_interna_de' => 'Transferência Interna De',
            'transferencia_interna_para' => 'Transferência Interna Para'
        ];

        return $values[$value];
    }

    // Accessors
    public function getMatDataConclusaoAttribute($value)
    {
        if ($value) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setMatDataConclusaoAttribute($value)
    {
        $this->attributes['mat_data_conclusao'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function getSituacaoMatriculaCursoAttribute($value)
    {
        return ucfirst($this->mat_situacao);
    }
}
