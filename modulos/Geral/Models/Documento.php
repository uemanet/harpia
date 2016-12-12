<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class Documento extends BaseModel
{
    protected $table = 'gra_documentos';

    protected $primaryKey = 'doc_id';

    protected $fillable = [
        'doc_pes_id',
        'doc_tpd_id',
        'doc_conteudo',
        'doc_data_expedicao',
        'doc_orgao',
        'doc_observacao',
        'doc_anx_documento'

    ];

    protected $searchable = [
        'doc_conteudo' => '='
    ];

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'doc_pes_id');
    }

    public function tipo_documento()
    {
        return $this->belongsTo('Modulos\Geral\Models\TipoDocumento', 'doc_tpd_id');
    }

    // // Accessors
    public function getDocDataExpedicaoAttribute($value)
    {
        if ($value === null){
          return null;
        }

        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    // Mutators
    public function setDocDataExpedicaoAttribute($value)
    {
        if ($value != null){
            $this->attributes['doc_data_expedicao'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
    }
}
