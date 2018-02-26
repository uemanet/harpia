<?php

namespace Modulos\Academico\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class MatrizCurricular extends BaseModel
{
    protected $table = 'acd_matrizes_curriculares';

    protected $primaryKey = 'mtc_id';

    protected $fillable = [
        'mtc_crs_id',
        'mtc_anx_projeto_pedagogico',
        'mtc_titulo',
        'mtc_descricao',
        'mtc_data',
        'mtc_creditos',
        'mtc_horas',
        'mtc_horas_praticas'
    ];

    protected $searchable = [
        'mtc_id' => '=',
        'mtc_crs_id' => '=',
        'mtc_titulo' => 'like'
    ];

    public function curso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Curso', 'mtc_crs_id', 'crs_id');
    }

    public function modulos()
    {
        return $this->hasMany('Modulos\Academico\Models\ModuloMatriz', 'mdo_mtc_id', 'mtc_id');
    }

    public function projeto()
    {
        return $this->hasOne('Modulos\Geral\Models\Anexo', 'anx_id', 'mtc_anx_projeto_pedagogico');
    }

    // Accessors
    // Retorna a data em padrao pt-BR em vez do padrao internacional
    public function getMtcDataAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setMtcDataAttribute($value)
    {
        if ($value) {
            $this->attributes['mtc_data'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
    }
}
