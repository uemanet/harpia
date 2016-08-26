<?php

namespace Modulos\Academico\Models;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Model\BaseModel;

class Turma extends BaseModel
{
    protected $table = 'acd_turmas';

    protected $primaryKey = 'trm_id';

    protected $fillable = [
        'trm_ofc_id',
        'trm_per_id',
        'trm_nome',
        'trm_qtd_vagas'
    ];

    protected $searchable = [
        'trm_nome' => 'like'
    ];

    public function oferta()
    {
        return $this->belongsTo('Modulos\Academico\Models\OfertaCurso', 'trm_ofc_id');
    }

    public function periodo()
    {
        return $this->belongsTo('Modulos\Academico\Models\PeriodoLetivo', 'trm_per_id');
    }

    public function getTrmOfcIdAttribute($value)
    {
        return DB::table('acd_ofertas_cursos')->where('ofc_id', $value)->value('ofc_ano');
    }

    public function getTrmPerIdAttribute($value)
    {
        return DB::table('acd_periodos_letivos')->where('per_id', $value)->value('per_nome');
    }
}
