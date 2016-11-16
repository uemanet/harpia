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
        'ofd_prf_id'
    ];

    protected $searchable = [
        'ofd_per_id' => 'like',
    ];

    public function periodoLetivo()
    {
        return $this->belongsTo('Modulos\Academico\Models\PeriodoLetivo', 'ofd_per_id', 'per_id');
    }

    public function modulosDisciplinas()
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
}
