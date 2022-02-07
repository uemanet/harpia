<?php

namespace Modulos\Academico\Repositories;

use DB;
use Illuminate\Support\Collection;
use Modulos\Academico\Models\Grupo;
use Modulos\Seguranca\Models\Auditoria;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Seguranca\Repositories\UsuarioRepository;

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

    public function verifyNameGrupo($grupoName, $idTurma, $grupoId = null)
    {
        $result = $this->model->where('grp_nome', $grupoName)->where('grp_trm_id', $idTurma)->get();

        if (!$result->isEmpty()) {
            if (!is_null($grupoId)) {
                $result = $result->where('grp_id', $grupoId);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }


    public function getMovimentacoes($grupoId)
    {
        $grupo = $this->find($grupoId);
        $result = new Collection();

        $usuarioRepository = new UsuarioRepository(new Usuario());

        $movimentacoes = $grupo->movimentacoes;

        foreach ($movimentacoes as $movimentacao) {
            $entry = $movimentacao->toArray();

            $entry["ttg_tut_id"] = $movimentacao->tutor->pessoa->pes_nome;
            $entry["ttg_data_inicio"] = $movimentacao->getRawOriginal('ttg_data_inicio');
            $entry["action"] = null;
            $entry["usuario"] = null;

            // Data hora default e a ultima modificacao
            $entry["data_hora"] = $entry["updated_at"];

            // Consulta a tabela de auditoria para verificar o usuario responsavel pela alteracao
            $data = DB::table('seg_auditoria')
                ->where('log_table', '=', 'acd_tutores_grupos')
                ->where('log_table_id', '=', $entry['ttg_id'])
                ->get();

            if (!$data->count()) {
                $result->push($entry);
                continue;
            }

            // Verifica o tipo de acao realizada
            foreach ($data as $item) {
                $entry["action"] = "Tutor removido do Grupo";

                if ($item->log_action == "INSERT") {
                    $entry["action"] = "Tutor inserido no Grupo";
                }

                $entry["data_hora"] = $item->created_at;
                $usuario = $usuarioRepository->find($item->log_usr_id);
                $entry["usuario"] = $usuario->pessoa->pes_nome;

                $result->push($entry);
            }
        }

        return $result;
    }
}
