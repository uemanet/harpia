<?php

namespace Modulos\Academico\Models;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Model\BaseModel;

class OfertaDisciplina extends BaseModel
{
    protected $table = 'acd_ofertas_disciplinas';

    protected $primaryKey = 'ofd_id';

    protected $fillable = [
        'ofd_mdc_id',
        'ofd_trm_id',
        'ofd_per_id',
        'ofd_prf_id',
        'ofd_qtd_vagas'
    ];

    protected $searchable = [
        'ofd_per_id' => 'like',
    ];

    public function periodoLetivo()
    {
        return $this->belongsTo('Modulos\Academico\Models\PeriodoLetivo', 'ofd_per_id', 'per_id');
    }

    public function moduloDisciplina()
    {
        return $this->belongsTo('Modulos\Academico\Models\ModuloDisciplina', 'ofd_mdc_id', 'mdc_id');
    }

    public function professor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Professor', 'ofd_prf_id', 'prf_id');
    }

    public function turma()
    {
        return $this->belongsTo('Modulos\Academico\Models\Turma', 'ofd_trm_id', 'trm_id');
    }

    public function matriculasOfertasDisciplinas()
    {
        return $this->hasMany('Modulos\Academico\Models\MatriculaOfertaDisciplina', 'mof_ofd_id', 'ofd_id');
    }

    public function mapeamentoItensNotas()
    {
        return $this->hasOne('Modulos\Integracao\Models\MapeamentoNota', 'min_ofd_id', 'ofd_id');
    }
}
