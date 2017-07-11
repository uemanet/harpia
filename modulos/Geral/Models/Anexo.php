<?php
namespace Modulos\Geral\Models;

use Illuminate\Support\Facades\Storage;
use Modulos\Core\Model\BaseModel;

class Anexo extends BaseModel
{
    protected $table = 'gra_anexos';

    protected $primaryKey = 'anx_id';

    protected $fillable = [
        'anx_nome',
        'anx_mime',
        'anx_extensao',
        'anx_localizacao'
    ];

    protected $searchable = [
        'anx_nome' => 'like'
    ];
}
