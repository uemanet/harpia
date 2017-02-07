<?php
namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Grupo;
use Modulos\Core\Repository\BaseRepository;
use DB;

class GrupoRepository extends BaseRepository
{
    public function __construct(Grupo $grupo)
    {
        $this->model = $grupo;
    }

    /**
     * PaginateRequest
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByTurma($turmaid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('grp_trm_id', '=', $turmaid)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }
        return $this->model->where('grp_trm_id', '=', $turmaid)->paginate(15);
    }

    /**
     * Busca um grupo específico de acordo com o seu Id
     *
     * @param $grupoid
     *
     * @return mixed
     */
    public function listsAllById($grupoid)
    {
        return $this->model->where('grp_id', $grupoid)->pluck('grp_nome', 'grp_id');
    }

    public function getAllByTurmaAndPolo($turmaId, $poloId)
    {
        return $this->model
                    ->where('grp_trm_id', '=', $turmaId)
                    ->where('grp_pol_id', '=', $poloId)
                    ->get();
    }

    public function findAllByTurma($TurmaId)
    {
        $entries = DB::table('acd_grupos')
            ->select('grp_id', 'grp_nome')
            ->where('grp_trm_id', '=', $TurmaId)
            ->get();

        return $entries;
    }

    public function verifyNameGrupo($grupoName, $idTurma)
    {
        $result = $this->model->where('grp_nome', $grupoName)->where('grp_trm_id', $idTurma)->get();

        if (!$result->isEmpty()) {
            return true;
        }

        return false;
    }
}
