<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\TipoDocumento;

class TipoDocumentoRepository extends BaseRepository
{
    public function __construct(TipoDocumento $tipo)
    {
        $this->model = $tipo;
    }

    public function listsTipoDocumentoByDocumentoId($documentoId)
    {
        return $this->model
                    ->join('gra_documentos', 'doc_tpd_id', 'tpd_id')
                    ->where('doc_id', '=', $documentoId)
                    ->pluck('tpd_nome', 'tpd_id');
    }
}
