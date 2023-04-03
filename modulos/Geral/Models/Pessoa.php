<?php

namespace Modulos\Geral\Models;

use Carbon\Carbon;
use Modulos\Academico\Models\Instituicao;
use Modulos\Core\Model\BaseModel;

class Pessoa extends BaseModel
{
    protected $table = 'gra_pessoas';

    protected $primaryKey = 'pes_id';

    protected $fillable = [
        'pes_itt_id',
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
        'pes_estrangeiro',
        'pes_cidade',
        'pes_bairro',
        'pes_estado',
        'pes_cep',
        'pes_numero',
        'pes_endereco',
        'pes_complemento'
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

    public function colaborador()
    {
        return $this->hasOne('Modulos\RH\Models\Colaborador', 'col_pes_id');
    }

    // Accessors
    public function getPesNascimentoAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    public function getPesEmailAttribute($value)
    {
        return strtolower($value);
    }

    public function getPesEstadoCivilAttribute($value)
    {
        if (!$value) {
            return "Não informado";
        }

        if (in_array($value, ['solteiro', 'casado', 'divorciado'])) {
            return ucfirst($value);
        }

        if ($value == "viuvo(a)") {
            return "Viúvo(a)";
        }

        if ($value == "uniao_estavel") {
            return "União estável";
        }
    }

    public function getInstituicaoSigla($instituicaoId){
        $instituicao = Instituicao::find($instituicaoId);
        return $instituicao->itt_sigla;
    }

    // Mutators
    public function setPesNascimentoAttribute($value)
    {
        if ($value) {
            $this->attributes['pes_nascimento'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
    }

    public function setPesEmailAttribute($value)
    {
        $this->attributes['pes_email'] = strtolower($value);
    }
}
