<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Turma;
use Modulos\Core\Repository\BaseRepository;

class TurmaRepository extends BaseRepository
{
    protected $cursoRepository;
    protected $periodoLetivoRepository;

    public function __construct(Turma $turma,
                                CursoRepository $cursoRepository,
                                PeriodoLetivoRepository $periodoLetivoRepository)
    {
        $this->model = $turma;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
    }

    public function findAllByOfertaCurso($ofertaCursoId)
    {
        $entries = $this->model
            ->where('trm_ofc_id', $ofertaCursoId)
            ->select('trm_id', 'trm_nome')
            ->get();

        return $entries;
    }

    public function findAllByOfertaCursoIntegrada($ofertaCursoId)
    {
        $entries = DB::table('int_ambientes_virtuais')
            ->join('int_ambientes_turmas', 'atr_amb_id', '=', 'amb_id')
            ->join('acd_turmas', 'atr_trm_id', '=', 'trm_id')
            ->where('trm_ofc_id', '=', $ofertaCursoId)
            ->get();

        return $entries;
    }

    public function findAllWithVagasDisponiveisByOfertaCurso($ofertaCursoId)
    {
        $entries = $this->model
            ->leftJoin('acd_matriculas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->select('acd_turmas.*', DB::raw('COUNT(mat_trm_id) as qtd_matriculas'))
            ->where('trm_ofc_id', '=', $ofertaCursoId)
            ->groupBy('trm_id')
            ->get();
        return $entries;
    }

    public function getCurso($turmaId)
    {
        $cursoId = DB::table('acd_ofertas_cursos')
            ->select('ofc_crs_id')
            ->join('acd_turmas', 'trm_ofc_id', '=', 'ofc_id')
            ->where('trm_id', '=', $turmaId)
            ->pluck('ofc_crs_id');

        $cursoId = $cursoId->toArray();
        return array_pop($cursoId);
    }

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

        $curso = $turma->ofertacurso->curso;

        return $curso;
    }

    /**
     * Busca uma turma específica de acordo com o seu Id
     *
     * @param $turmaid
     *
     * @return mixed
     */
    public function listsAllById($turmaid)
    {
        return $this->model->where('trm_id', $turmaid)->pluck('trm_nome', 'trm_id');
    }

    /**
     * @param $turmaid
     *
     * Busca os polos de todos baseados nas informações dos alunos matriculados na turma
     *
     * @return mixed
     */
    public function getTurmaPolosByMatriculas($turmaid)
    {
        return $this->model->select('pol_id', 'pol_nome')
            ->join('acd_matriculas', 'mat_trm_id', '=', 'trm_id')
            ->join('acd_polos', 'mat_pol_id', '=', 'pol_id')
            ->where('trm_id', '=', $turmaid)
            ->groupby('pol_id')
            ->get();
    }

    /**
     * @param Turma $turma
     * @return mixed|string
     */
    public function shortName(Turma $turma)
    {
        $cursoId = $this->getCurso($turma->trm_id);
        $curso = $this->cursoRepository->find($cursoId);
        $periodoLetivo = $this->periodoLetivoRepository->find($turma->trm_per_id);

        $shortName = $curso->crs_sigla .' ' . $turma->trm_nome . ' ' . $periodoLetivo->per_nome;
        $shortName = str_replace(' ', '_', $shortName);

        return $shortName;
    }

    /**
     * @param Turma $turma
     * @return string
     */
    public function fullName(Turma $turma)
    {
        $cursoId = $this->getCurso($turma->trm_id);
        $curso = $this->cursoRepository->find($cursoId);
        $periodoLetivo = $this->periodoLetivoRepository->find($turma->trm_per_id);

        $fullname = $curso->crs_nome . ' - ' . $turma->trm_nome . ' - ' . $periodoLetivo->per_nome;

        return $fullname;
    }
}
