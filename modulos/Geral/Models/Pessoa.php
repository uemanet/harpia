<?php

namespace Modulos\Geral\Models;

use Carbon\Carbon;
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

    // Accessors
    public function getPesNascimentoAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    // Mutators
    public function setPesNascimentoAttribute($value)
    {
        $this->attributes['pes_nascimento'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}
