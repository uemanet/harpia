<?php
namespace Modulos\Geral\Models;


use Modulos\Core\Model\BaseModel;

class TipoDocumento extends BaseModel
{

    protected $table = 'gra_tipos_documentos';

    protected $primaryKey = 'tpd_id';

    protected $fillable = [
        'tpd_nome'
    ];
}