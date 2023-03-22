<?php

namespace Modulos\Academico\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modulos\Core\Model\BaseModel;

class Instituicao extends BaseModel
{
    protected $table = 'acd_instituicoes';

    protected $primaryKey = 'itt_id';

    protected $fillable = [
        'itt_nome',
        'itt_sigla'
    ];

    protected $searchable = [
        'itt_nome' => 'like',
        'itt_sigla' => 'like'
    ];

    public function turmas()
    {
        return $this->hasMany('Modulos\Academico\Models\Turma', 'trm_itt_id', 'itt_id');
    }

    public function pessoas()
    {
        return $this->hasMany('Modulos\Geral\Models\Pessoa', 'pes_itt_id', 'itt_id');
    }
}
