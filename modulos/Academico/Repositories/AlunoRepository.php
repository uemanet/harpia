<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Aluno;
use Modulos\Core\Repository\BaseRepository;

class AlunoRepository extends BaseRepository
{
    public function __construct(Aluno $aluno)
    {
        $this->model = $aluno;
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('gra_pessoas', function ($join) {
            $join->on('alu_pes_id', '=', 'pes_id');
        })->leftJoin('gra_documentos', function ($join) {
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
}
