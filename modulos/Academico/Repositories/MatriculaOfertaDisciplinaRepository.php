<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Core\Repository\BaseRepository;
use DB;

class MatriculaOfertaDisciplinaRepository extends BaseRepository
{
    public function __construct(MatriculaOfertaDisciplina $matricula)
    {
        $this->model = $matricula;
    }

    public function getDisciplinasCursadasByAluno($alunoId)
    {
        $query = $this->model
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->where('mat_alu_id', '=', $alunoId);

        return $query->get();
    }

    public function getDisciplinasOfertadasAndCursadasByAluno($alunoId, $turmaId, $periodoId)
    {
        // pega as disciplinas cursadas pelo aluno
        $disciplinasCursadas = implode(",", $this->getDisciplinasCursadasByAluno($alunoId)->pluck('mof_ofd_id')->toArray());

        // pega as disciplinas ofertadas no periodo e turma correspondentes, e verifica se o aluno
        // está matriculado ou não em cada disciplina
        $query = DB::table('acd_ofertas_disciplinas')
                    ->join('acd_modulos_disciplinas', function ($join) {
                        $join->on('ofd_mdc_id', '=', 'mdc_id');
                    })
                    ->join('acd_disciplinas', function ($join) {
                        $join->on('mdc_dis_id', '=', 'dis_id');
                    })
                    ->join('acd_professores', function ($join) {
                        $join->on('ofd_prf_id', '=', 'prf_id');
                    })
                    ->join('gra_pessoas', function ($join) {
                        $join->on('prf_pes_id', '=', 'pes_id');
                    })
                    ->where('ofd_per_id', '=', $periodoId)
                    ->where('ofd_trm_id', '=', $turmaId);

        $case = 'CASE WHEN ofd_id IN ('.$disciplinasCursadas.') THEN 1 ELSE 0 END AS matriculado';

        if ($disciplinasCursadas == '') {
            $case = 'CASE WHEN ofd_id IN (0) THEN 1 ELSE 0 END AS matriculado';
        }

        $disciplinasOfertadas =  $query->select(
            'ofd_id',
            'dis_nome',
            'dis_creditos',
            'dis_carga_horaria',
            'ofd_qtd_vagas',
            'pes_nome',
            DB::raw($case)
        )->get();


        if ($disciplinasOfertadas->count()) {
            for ($i=0;$i<$disciplinasOfertadas->count();$i++) {
                $quantMatriculas = $this->model->where('mof_ofd_id', '=', $disciplinasOfertadas[$i]->ofd_id)->count();
                $disciplinasOfertadas[$i]->quant_matriculas = $quantMatriculas;
            }
        }

        return $disciplinasOfertadas;
    }

    public function verifyMatriculaDisciplina($matriculaId, $ofertaId)
    {
        $query = $this->model->where('mof_ofd_id', '=', $ofertaId)
                             ->where('mof_mat_id', '=', $matriculaId);

        return $query->first();
    }

    public function verifyQtdVagas($ofertaId)
    {
        $query = $this->model
                    ->join('acd_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
                    ->where('mof_ofd_id', '=', $ofertaId)->get();

        if ($query->count()) {
            $vagas = $query[0]->ofd_qtd_vagas;
            $qtd = $query->count();

            if (($vagas == $qtd)) {
                return false;
            }
        }

        return true;
    }
}
