<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\TipoDocumento;
use DB;

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

    public function listsTiposDocumentosWithoutPessoa($pessoaId)
    {
        $entries = DB::table('gra_documentos')
                    ->where('doc_pes_id', '=', $pessoaId)
                    ->get();

        $tiposId = [];

        foreach ($entries as $key => $value) {
            $tiposId[] = $value->doc_tpd_id;
        }

        $result = $this->model
            ->whereNotIn('tpd_id', $tiposId)
            ->pluck('tpd_nome', 'tpd_id');

        return $result;
    }
}
