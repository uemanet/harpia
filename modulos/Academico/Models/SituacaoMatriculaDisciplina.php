<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class SituacaoMatriculaDisciplina extends BaseModel
{
    protected $table = 'acd_situacoes_matricula_disciplina';

    protected $fillable = ['stm_nome'];
}