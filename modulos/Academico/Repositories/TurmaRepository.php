<?php

namespace Modulos\Academico\Repositories;


use Illuminate\Support\Facades\DB;
use Modulos\Academico\Models\Turma;
use Modulos\Core\Repository\BaseRepository;

class TurmaRepository extends BaseRepository
{
    public function __construct(Turma $turma)
    {
        $this->model = $turma;
    }

    public function findAllByOfertaCurso($ofertaCursoId)
    {
        $entries = DB::table('acd_turmas')
                        ->select('trm_id', 'trm_nome')
                        ->where('trm_ofc_id', '=', $ofertaCursoId)
                        ->get();

        return $entries;
    }
<<<<<<< HEAD


    /**
     * PaginateRequest
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByOferta($ofertaid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('trm_ofc_id', '=', $ofertaid)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }
        return $this->model->where('trm_ofc_id', '=', $ofertaid)->paginate(15);
    }

    public function findCursoByTurma($turmaId)
    {
        $turma = $this->find($turmaId);

        $curso = $turma->oferta->curso;

        return $curso;
    }

=======
>>>>>>> 7257e809820b5c9a0cda190b05601d2a1e07f91e
}
