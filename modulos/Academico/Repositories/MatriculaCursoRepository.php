<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;

class MatriculaCursoRepository extends BaseRepository
{
    public function __construct(Matricula $matricula)
    {
        $this->model = $matricula;
    }

    public function verifyIfExistsMatriculaByOfertaCursoOrTurma($alunoId, $ofertaCursoId, $turmaId)
    {
        $result = $this->model
                        ->join('acd_turmas', function ($join) {
                            $join->on('mat_trm_id', '=', 'trm_id');
                        })
                        ->join('acd_ofertas_cursos', function ($join) {
                            $join->on('trm_ofc_id', '=', 'ofc_id');
                        })
                        ->where('mat_alu_id', '=', $alunoId)
                        ->where(function ($query) use ($turmaId, $ofertaCursoId) {
                            $query->where('mat_trm_id', '=', $turmaId)
                                    ->orWhere('trm_ofc_id', '=', $ofertaCursoId);
                        })->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function verifyIfExistsMatriculaByCursoAndSituacao($alunoId, $cursoId)
    {
        $result = $this->model
                        ->join('acd_turmas', function ($join) {
                            $join->on('mat_trm_id', '=', 'trm_id');
                        })
                        ->join('acd_ofertas_cursos', function ($join) {
                            $join->on('trm_ofc_id', '=', 'ofc_id');
                        })
                        ->where('mat_alu_id', '=', $alunoId)
                        ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente'])
                        ->where('ofc_crs_id', '=', $cursoId)
                        ->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function verifyIfExistsMatriculaInCursoGraducao($alunoId)
    {
        $result = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id')->where('crs_nvc_id', '=', '3');
            })
            ->where('mat_alu_id', '=', $alunoId)
            ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente'])
            ->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function findAll(array $options, array $select = null)
    {
        $query = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join){
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join){
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->leftJoin('acd_polos', function ($join){
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->leftJoin('acd_grupos', function ($join){
                $join->on('mat_grp_id', '=', 'grp_id');
            });

        if(!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
        }

        if(!is_null($select)) {
            $query = $query->select($select);
        }

        return $query->get();
    }
}
