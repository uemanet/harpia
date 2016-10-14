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

    public function findPessoaByCpf($cpf){

        $result = $this->model->join('gra_documentos', function ($join) {
                        $join->on('pes_id', '=', 'doc_pes_id');
                    })->join('gra_tipos_documentos', function ($join) {
                        $join->on('doc_tpd_id', '=', 'tpd_id');
                    })->where('tpd_nome', '=', 'CPF')
                        ->where('doc_conteudo','=',$cpf)->select('pes_id')->get();

        return $result;
    }

    public function findByIdForForm($id)
    {
        $result = $this->model
                        ->leftJoin('gra_documentos', function ($join) {
                            $join->on('pes_id', '=', 'doc_pes_id')
                                ->where('doc_tpd_id', '=', 2);
                        })
                        ->select('gra_pessoas.*', 'doc_conteudo as pes_cpf')
                        ->where('pes_id', '=', $id)
                        ->first();

        return $result;
    }
}
