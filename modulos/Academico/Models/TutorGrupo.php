<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class TutorGrupo extends BaseModel
{
    protected $table = 'acd_tutores_grupos';

    protected $primaryKey = 'ttg_id';

    protected $fillable = [
        'ttg_tut_id',
        'ttg_grp_id',
        'ttg_tipo_tutoria',
        'ttg_data_inicio',
        'ttg_data_fim'
    ];

    protected $searchable = [
        'ttg_tipo_tutoria' => 'like'
    ];

    public function tutor()
    {
        return $this->belongsTo('Modulos\Academico\Models\Tutor', 'ttg_tut_id', 'tut_id');
    }

    public function grupo()
    {
        return $this->belongsTo('Modulos\Academico\Models\Grupo', 'ttg_grp_id', 'grp_id');
    }

    // Accessors
    public function getTtgDataInicioAttribute($value)
    {
        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    public function getTtgTipoTutoriaAttribute($value)
    {
        if ($value) {
            if ($value == "distancia") {
                return "Distância";
            }
            
            return ucfirst($value);
        }
    }

    // Mutators
    public function setTtgDataInicioAttribute($value)
    {
        $this->attributes['ttg_data_inicio'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}
