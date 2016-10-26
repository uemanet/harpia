<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Pessoa;

class PessoaRepository extends BaseRepository
{
    public function __construct(Pessoa $pessoa)
    {
        $this->model = $pessoa;
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        });

        if (!empty($search)) {
            foreach ($search as $value) {
                if ($value['field'] == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value['term']);
                    continue;
                }

                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        $result = $result->paginate(15);

        return $result;
    }

    public function verifyEmail($email, $idPessoa = null)
    {
        $result = $this->model->where('pes_email', $email)->get();

        if (!$result->isEmpty()) {
            if (!is_null($idPessoa)) {
                $result = $result->where('pes_id', $idPessoa);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function update(array $data, $id, $attribute = "pes_id")
    {
        $pessoa = $this->model->find($id);

        return $pessoa->fill($data)->save();
    }

    public function findPessoaByCpf($cpf)
    {
        $result = $this->model->join('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')
                                ->where('doc_tpd_id', '=', 2);
        })->where('doc_conteudo', '=', $cpf)->select('pes_id')->first();

        return $result;
    }

    public function findById($id)
    {
        $result = $this->model
                        ->leftJoin('gra_documentos', function ($join) {
                            $join->on('pes_id', '=', 'doc_pes_id')
                                ->where('doc_tpd_id', '=', 2);
                        })
                        ->select('gra_pessoas.*', 'doc_conteudo')
                        ->where('pes_id', '=', $id)
                        ->first();

        return $result;
    }
}
