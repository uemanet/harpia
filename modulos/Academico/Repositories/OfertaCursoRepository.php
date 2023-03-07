<?php

namespace Modulos\Academico\Repositories;

use DB;
use Auth;
use Modulos\Academico\Models\OfertaCurso;
use Modulos\Core\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class OfertaCursoRepository extends BaseRepository
{
    public function __construct(OfertaCurso $ofertacurso)
    {
        parent::__construct($ofertacurso);
    }

    /**
     * Cria uma nova oferta de curso, de acordo com regras de validação
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $oferta = null;

        // verifica se existe um registro com mesmo ano, modalidade e curso
        $entry = $this->model
            ->where([
                ['ofc_ano', '=', $data['ofc_ano']],
                ['ofc_mdl_id', '=', $data['ofc_mdl_id']],
                ['ofc_crs_id', '=', $data['ofc_crs_id']]
            ])->first();

        if (!$entry) {
            $oferta = $this->model->create($data);

            if (isset($data['polos']) && !is_null($data['polos'])) {
                foreach ($data['polos'] as $id) {
                    $oferta->polos()->attach($id);
                }
            }
        }

        return $oferta;
    }

    /**
     * @param array $data
     * @param $id
     * @param null $attribute
     * @return array|int|null
     */
    public function update(array $data, $id, $attribute = null)
    {
        $oferta = $this->find($id);

        // Polos com grupos vinculados
        $polosComGruposVinculados = $this->polosVinculadosGrupos($oferta->turmas)->toArray();

        // Polos vinculados com a oferta
        $polosVinculados = DB::table('acd_polos_ofertas_cursos')
            ->where('poc_ofc_id', '=', $oferta->ofc_id)
            ->pluck('poc_pol_id')->toArray();

        $avisos = null;

        if (isset($data['polos']) && !is_null($data['polos'])) {
            // Polos que podem ser removidos da oferta
            // Apenas polos sem grupos vinculados
            $remover = array_diff($polosVinculados, $polosComGruposVinculados);

            foreach ($polosComGruposVinculados as $polo) {
                if (!in_array($polo, $data['polos'])) {
                    $avisos = array('type' => 'warning', 'message' => 'Alguns polos contém grupos vinculados e não foram removidos');
                    break;
                }
            }

            foreach ($remover as $key => $polo) {
                $oferta->polos()->detach($polo);
            }

            // Redefine os polos a serem vinculados
            $revinculo = array_diff($data['polos'], $polosComGruposVinculados);

            foreach ($revinculo as $polo) {
                $oferta->polos()->attach($polo);
            }
        }

        unset($data['polos']);
        $update = $oferta->fill($data)->save();

        if ($avisos) {
            return $avisos;
        }

        return $update;
    }

    /**
     * Paginate
     * @param null $sort
     * @param null $search
     * @return mixed
     */
    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
            ->join('acd_cursos', 'ofc_crs_id', '=', 'crs_id');

        if (!empty($search)) {
            foreach ($search as $key => $value) {
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

        $user = Auth::user();
        if (!$user->isAdmin()){

            $ofertas = DB::table('acd_turmas')
                ->join('acd_ofertas_cursos', 'ofc_id', 'trm_ofc_id')
                ->where('trm_itt_id', '=', $user->pessoa->pes_itt_id)
                ->groupBy('ofc_id')->distinct('ofc_id')->get();

            $ofc_ids = $ofertas->pluck('ofc_id')->toArray();

            $result = $result->whereIn('ofc_id', $ofc_ids);
        }
        return $result->paginate(15);
    }

    /**
     * Busca todas as ofertas de curso de acordo com o curso informado
     * @param $cursoid
     * @return mixed
     */
    public function findAllByCurso($cursoid)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            $ofertas = DB::table('acd_turmas')
                ->join('acd_ofertas_cursos', 'ofc_id', 'trm_ofc_id')
                ->where('trm_itt_id', '=', $user->pessoa->pes_itt_id)
                ->where('ofc_crs_id', $cursoid)
                ->groupBy('ofc_id')->distinct('ofc_id')->get();

            $ofc_ids = $ofertas->pluck('ofc_id')->toArray();

            return $this->model
                ->join('acd_modalidades', 'ofc_mdl_id', '=', 'mdl_id')
                ->whereIn('ofc_id', $ofc_ids)
                ->get(['ofc_id', 'ofc_ano', 'mdl_nome']);
        }

        return $this->model
            ->join('acd_modalidades', 'ofc_mdl_id', '=', 'mdl_id')
            ->where('ofc_crs_id', $cursoid)
            ->get(['ofc_id', 'ofc_ano', 'mdl_nome']);
    }

    /**
     * Busca todas as ofertas de curso de acordo com o curso informado sem a modalidade presencial
     * @param $cursoid
     * @return mixed
     */
    public function findAllByCursoWithoutPresencial($cursoid)
    {
        return $this->model
            ->join('acd_modalidades', 'ofc_mdl_id', '=', 'mdl_id')
            ->where([
                ['ofc_crs_id', '=', $cursoid],
                ['ofc_mdl_id', '<>', 1]
            ])
            ->get(['ofc_id', 'ofc_ano', 'mdl_nome']);
    }

    /**
     * Busca todas as ofertas de curso de acordo com o curso informado sem a modalidade semi-presencial ou ead
     * @param $cursoid
     * @return mixed
     */
    public function findAllByCursoWithoutEad($cursoid)
    {
        return $this->model
            ->join('acd_modalidades', 'ofc_mdl_id', '=', 'mdl_id')
            ->where([
                ['ofc_crs_id', '=', $cursoid],
                ['ofc_mdl_id', '=', 1]
            ])
            ->get(['ofc_id', 'ofc_ano', 'mdl_nome']);
    }


    /**
     * Busca uma oferta de curso específica de acordo com o seu Id
     * @deprecated
     * @param $ofertaid
     * @return mixed
     */
    public function listsAllById($ofertaid)
    {
        return $this->model->where('ofc_id', $ofertaid)->pluck('ofc_ano', 'ofc_id');
    }

    private function polosVinculadosGrupos(Collection $turmas)
    {
        $polos = [];

        foreach ($turmas as $turma) {
            $grupos = $turma->grupos;

            foreach ($grupos as $grupo) {
                $polos[] = $grupo->grp_pol_id;
            }
        }

        $polos = collect($polos);

        // Retorna os polos vinculados
        return $polos->unique();
    }
}
