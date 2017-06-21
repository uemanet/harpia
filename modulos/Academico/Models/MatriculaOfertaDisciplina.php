<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class MatriculaOfertaDisciplina extends BaseModel
{
    protected $table = 'acd_matriculas_ofertas_disciplinas';

    protected $primaryKey = 'mof_id';

    protected $fillable = [
        'mof_mat_id',
        'mof_ofd_id',
        'mof_tipo_matricula',
        'mof_nota1',
        'mof_nota2',
        'mof_nota3',
        'mof_conceito',
        'mof_recuperacao',
        'mof_final',
        'mof_mediafinal',
        'mof_situacao_matricula'
    ];

    protected $searchable = [
        'mof_ofd_id' => '=',
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '='
    ];

    public function matriculaCurso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Matricula', 'mof_mat_id', 'mat_id');
    }

    public function ofertaDisciplina()
    {
        return $this->belongsTo('Modulos\Academico\Models\OfertaDisciplina', 'mof_ofd_id', 'ofd_id');
    }

    public function getSituacaoMatriculaAttribute($value)
    {
        if ($this->mof_situacao_matricula == 'aprovado_media') {
            return 'Aprovado por média';
        }
        if ($this->mof_situacao_matricula == 'aprovado_final') {
            return 'Aprovado por final';
        }
        if ($this->mof_situacao_matricula == 'reprovado_media') {
            return 'Reprovado por média';
        }
        if ($this->mof_situacao_matricula == 'reprovado_final') {
            return 'Reprovado por final';
        }
        if ($this->mof_situacao_matricula == 'cursando') {
            return 'Cursando';
        }
        if ($this->mof_situacao_matricula == 'cancelado') {
            return 'Cancelado';
        }
    }
}
