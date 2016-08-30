<?php
namespace Modulos\Geral\Models;

use Illuminate\Support\Facades\Storage;
use Modulos\Core\Model\BaseModel;

class Anexo extends BaseModel
{
    protected $table = 'gra_anexos';

    protected $primaryKey = 'anx_id';

    protected $fillable = [
        'anx_tax_id',
        'anx_nome',
        'anx_mime',
        'anx_localizacao'
    ];

    protected $searchable = [
        'anx_nome' => 'like'
    ];

    /**
     * Retorna o caminho do arquivo
     * @return string
     */
    public function filePath()
    {
        //return Storage::allFiles($this->attributes(['anx_localizacao']).DIRECTORY_SEPARATOR);
        return $this->attributes['anx_localizacao'].DIRECTORY_SEPARATOR;
    }
}
