<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class Curso extends BaseModel
{
    protected $table = 'acd_cursos';

    protected $primaryKey = 'crs_id';

    protected $fillable = [
        'crs_dep_id',
        'crs_nvc_id',
        'crs_prf_diretor',
        'crs_nome',
        'crs_sigla',
        'crs_descricao',
        'crs_resolucao',
        'crs_autorizacao',
        'crs_data_autorizacao',
        'crs_eixo',
        'crs_habilitacao'
    ];

    protected $searchable = [
        'crs_nome' => 'like'
    ];

    public function departamento()
    {
        return $this->belongsTo('Modulos\Academico\Models\Departamento', 'crs_dep_id', 'dep_id');
    }

    public function diretor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Professor', 'crs_prf_diretor', 'prf_id');
    }

    public function nivelcurso()
    {
        return $this->belongsTo('Modulos\Academico\Models\NivelCurso', 'crs_nvc_id', 'nvc_id');
    }

    public function matrizes()
    {
        return $this->hasMany('Modulos\Academico\Models\MatrizCurricular', 'mtc_crs_id', 'crs_id');
    }

    public function ofertas()
    {
        return $this->hasMany('Modulos\Academico\Models\OfertaCurso', 'ofc_crs_id', 'crs_id');
    }

    // Accessors
    public function getCrsDataAutorizacaoAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        $date = Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        return $date;
    }
}
