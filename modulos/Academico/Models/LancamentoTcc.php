<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class LancamentoTcc extends BaseModel
{
    protected $table = 'acd_lancamentos_tccs';

    protected $primaryKey = 'ltc_id';

    protected $fillable = [
        'ltc_prf_id',
        'ltc_titulo',
        'ltc_tipo',
        'ltc_data_apresentacao',
        'ltc_observacao'
    ];

    protected $searchable = [
        'ltc_titulo' => 'like'
    ];

    public function professor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Professor', 'ltc_prf_id', 'prf_id');
    }

    public function matriculaCurso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Matricula', 'ltc_id', 'mat_ltc_id');
    }

    // Accessors
    public function getLtcDataApresentacaoAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    // Mutators
    public function setLtcDataApresentacaoAttribute($value)
    {
        $this->attributes['ltc_data_apresentacao'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}
