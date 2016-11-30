<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class Professor extends BaseModel
{
    protected $table = 'acd_professores';

    protected $primaryKey = 'prf_id';

    protected $fillable = [
        'prf_pes_id',
    ];

    protected $searchable = [
        'pes_nome' => 'like',
        'pes_email' => 'like',
        'pes_cpf' => '='
    ];

    public function centro()
    {
        return $this->hasOne('Modulos\Academico\Models\Centro', 'cen_prf_diretor');
    }

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'prf_pes_id');
    }

    public function ofertasDisciplinas()
    {
        return $this->hasMany('Modulos\Geral\Models\OfertaDisciplina', 'ofd_prf_id', 'prf_id');
    }
}
