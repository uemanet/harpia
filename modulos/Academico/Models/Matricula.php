<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Matricula extends BaseModel
{
    protected $table = 'acd_matriculas';

    protected $primaryKey = 'mat_id';

    protected $fillable = [
        'mat_alu_id',
        'mat_trm_id',
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

}