<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;
use DB;

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
                        })
                        ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente'])
                        ->get();

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
    
    public function verifyExistsVagasByTurma($turmaId)
    {
        $result = $this->model
                        ->rightJoin('acd_turmas', function ($join) {
                            $join->on('mat_trm_id', '=', 'trm_id');
                        })
                        ->select('trm_qtd_vagas', DB::raw('COUNT(mat_trm_id) as qtd_matriculas'))
                        ->where('trm_id', '=', $turmaId)
                        ->groupBy('trm_id')
                        ->first();

        if($result) {
            if($result->qtd_matriculas < $result->trm_qtd_vagas) {
                return true;
            }
        }

        return false;
    }

    public function listsCursosNotMatriculadoByAluno($alunoId)
    {
        $cursosMatriculados = $this->model
                                    ->join('acd_turmas', function ($join) {
                                        $join->on('mat_trm_id', '=', 'trm_id');
                                    })
                                    ->join('acd_ofertas_cursos', function ($join) {
                                        $join->on('trm_ofc_id', '=', 'ofc_id');
                                    })
                                    ->join('acd_cursos', function ($join) {
                                        $join->on('ofc_crs_id', '=', 'crs_id');
                                    })
                                    ->select('crs_id')
                                    ->where('mat_alu_id', '=', $alunoId)
                                    ->whereIn('mat_situacao', ['cursando'])
                                    ->pluck('crs_id')
                                    ->toArray();

        $query = DB::table('acd_cursos')
                    ->whereNotIn('crs_id', $cursosMatriculados);

        if($this->verifyIfExistsMatriculaInCursoGraducao($alunoId)) {
            $query = $query->where('crs_nvc_id', '<>', 3);
        }

        return $query->pluck('crs_nome', 'crs_id');
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

    public function createMatricula($alunoId, array $options)
    {
        // verifica se aluno possui matricula na oferta de curso ou na turma
        if ($this->verifyIfExistsMatriculaByOfertaCursoOrTurma($alunoId, $options['ofc_id'], $options['mat_trm_id'])) {
            return array(
                'type' => 'error',
                'message' => 'Aluno já possui matricula na oferta ou turma'
            );
        }

        // verifica se aluno possui matricula ativa no curso, mesmo sendo em ofertas diferentes, contanto que tenha concluido, evadido
        // ou abandonado o curso
        if ($this->verifyIfExistsMatriculaByCursoAndSituacao($alunoId, $options['crs_id'])) {
            return array(
                'type' => 'error',
                'message' => 'Aluno já possui matricula ativa no curso selecionado'
            );
        }

        // Verifica o nivel do curso, e caso seja de GRADUACAO, verifica se o aluno possui matrícula em algum curso de graduacao
        $curso = DB::table('acd_cursos')->where('crs_id', $options['crs_id'])->first();

        // caso seja de Graducao
        if ($curso->crs_nvc_id == 3) {
            if ($this->verifyIfExistsMatriculaInCursoGraducao($alunoId)) {
                return array(
                    'type' => 'error',
                    'message' => 'Aluno já possui matricula ativa em outro curso de graduação'
                );
            }
        }

        // verifica se a turma ainda possui vagas disponiveis
        if(!$this->verifyExistsVagasByTurma($options['mat_trm_id'])) {
            return array(
                'type' => 'error',
                'message' => 'A turma escolhida não possui mais vagas disponiveis'
            );
        }

        $dataMatricula = [
            'mat_alu_id' => $alunoId,
            'mat_trm_id' => $options['mat_trm_id'],
            'mat_pol_id' => $options['mat_pol_id'],
            'mat_grp_id' => ($options['mat_grp_id'] == '') ? null : $options['mat_grp_id'],
            'mat_modo_entrada' => $options['mat_modo_entrada'],
            'mat_situacao' => 'cursando'
        ];

        if($this->create($dataMatricula)) {
            return array(
                'type' => 'success',
                'message' => 'Matricula efetuada com sucesso!'
            );
        }

        return array(
            'type' => 'error',
            'message' => 'Erro ao tentar matricular aluno'
        );
    }
}
