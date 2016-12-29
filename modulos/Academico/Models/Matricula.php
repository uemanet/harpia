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
        'mat_situacao'
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

    // Retorna a data em padrao pt-BR em vez do padrao internacional
    public function getMatDataConclusaoAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

}
