<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Documento;

class DocumentoRepository extends BaseRepository
{

    public function __construct(Documento $documento)
    {
        $this->model = $documento;
    }

    public function getCpfByPessoa($pessoaId)
    {
        return $this->model
                    ->join('gra_tipos_documentos', 'doc_tpd_id', 'tpd_id')
                    ->where('doc_pes_id', '=', $pessoaId)
                    ->where('tpd_nome', 'CPF')
                    ->get();
    }
}