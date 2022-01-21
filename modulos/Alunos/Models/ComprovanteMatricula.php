<?php

namespace Modulos\Alunos\Models;

use Modulos\Core\Model\BaseModel;

class ComprovanteMatricula extends BaseModel
{
    protected $table = 'aln_comprovantes_matriculas';

    protected $primaryKey = 'aln_id';

    protected $fillable = [
        'aln_mat_id',
        'aln_dados_matricula',
        'aln_codigo'
    ];

    public function matriculaCurso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Matricula', 'aln_mat_id', 'mat_id');
    }
}
