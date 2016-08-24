<?php

namespace Modulos\Academico\Models;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class MatrizCurricular extends BaseModel
{
    protected $table = 'acd_matrizes_curriculares';

    protected $primaryKey = 'mtc_id';

    protected $fillable = [
        'mtc_crs_id',
        'mtc_anx_projeto_pedagogico',
        'mtc_descricao',
        'mtc_data',
        'mtc_creditos',
        'mtc_horas',
        'mtc_horas_praticas'
    ];

    protected $searchable = [
        'mtc_id' => '=',
    ];

    public function curso()
    {
        return $this->belongsTo('Modulos\Academico\Models\Curso', 'mtc_crs_id');
    }

    // Accessors
    // Retorna a data em padrao pt-BR em vez do padrao internacional
    public function getMtcDataAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    // Retorna o nome do curso em vez do id do curso
    public function getMtcCrsIdAttribute($value)
    {
        return DB::table('acd_cursos')->where('crs_id', $value)->value('crs_nome');
    }

    // Mutators
    public function setMtcDataAttribute($value)
    {
        $this->attributes['mtc_data'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

}
