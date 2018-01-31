<?php

namespace Modulos\Geral\Repositories;

use DB;
use Modulos\Geral\Models\Anexo;
use Modulos\Geral\Models\Documento;
use Modulos\Core\Repository\BaseRepository;

class DocumentoRepository extends BaseRepository
{
    public function __construct(Documento $documento)
    {
        $this->model = $documento;
    }

    public function verifyCpf($cpf, $idPessoa = null)
    {
        $result = $this->model->where('doc_conteudo', $cpf)->where('doc_tpd_id', 2)->get();

        if (!$result->isEmpty()) {
            if (!is_null($idPessoa)) {
                $result = $result->where('doc_pes_id', $idPessoa);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }
    }

    public function updateDocumento(array $data, array $options)
    {
        $query = $this->model;

        foreach ($options as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        $updated = 0;
        $registros = $query->get();

        if ($registros) {
            foreach ($registros as $obj) {
                $obj->fill($data);
                $obj->save();
            }

            $updated = $registros->count();
        }

        return $updated;
    }

    public function deleteDocumento($documentoId)
    {
        try {
            $anexoId = DB::table('gra_documentos')->where('doc_id', '=', $documentoId)->pluck('doc_anx_documento')->toArray();

            $this->update(['doc_anx_documento' => null], $documentoId);

            if ($anexoId) {
                $anexoRepository = new AnexoRepository(new Anexo());
                $result = $anexoRepository->deletarAnexo(array_pop($anexoId));
                return $result;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateOrCreate(array $attributes, array $data)
    {
        return $this->model->updateOrCreate($attributes, $data);
    }

    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        if (isset($data['doc_data_expedicao'])) {
            $data['doc_data_expedicao'] = ($data['doc_data_expedicao'] != "") ? $data['doc_data_expedicao'] : null;
        }

        $updated = 0;
        $registros = $this->model->where($attribute, '=', $id)->get();

        if ($registros) {
            foreach ($registros as $obj) {
                $obj->fill($data)->save();
            }

            $updated = $registros->count();
        }

        return $updated;
    }
}
