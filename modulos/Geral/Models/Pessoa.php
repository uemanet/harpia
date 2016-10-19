<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Pessoa extends BaseModel
{
    protected $table = 'gra_pessoas';

    protected $primaryKey = 'pes_id';

    protected $fillable = [
        'pes_nome',
        'pes_sexo',
        'pes_email',
        'pes_telefone',
        'pes_nascimento',
        'pes_mae',
        'pes_pai',
        'pes_estado_civil',
        'pes_naturalidade',
        'pes_nacionalidade',
        'pes_raca',
        'pes_necessidade_especial',
        'pes_estrangeiro'
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => '=',
        'pes_cpf' => '='
    ];

    public function documentos()
    {
        return $this->hasMany('Modulos\Geral\Models\Documento', 'doc_pes_id');
    }

    public function titulacoes_informacoes()
    {
        return $this->hasMany('Modulos\Geral\Models\TitulacaoInformacao', 'tin_pes_id');
    }

    public function usuario()
    {
        return $this->hasOne('Modulos\Seguranca\Models\Usuario', 'usr_pes_id');
    }

    public function tutor()
    {
        return $this->hasOne('Modulos\Academico\Models\Tutor', 'tut_pes_id');
    }

    public function aluno()
    {
        return $this->hasOne('Modulos\Academico\Models\Aluno', 'alu_pes_id');
    }

    public function professor()
    {
        return $this->hasOne('Modulos\Academico\Models\Professor', 'prf_pes_id');
    }
}
