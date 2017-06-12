<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Collection;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Core\Repository\BaseRepository;
use DB;

class MatriculaOfertaDisciplinaRepository extends BaseRepository
{
    protected $moduloDisciplinaRepository;
    protected $ofertaDisciplinaRepository;
    private $alunoRepository;

    public function __construct(
        MatriculaOfertaDisciplina $matricula,
        ModuloDisciplinaRepository $modulo,
        OfertaDisciplinaRepository $oferta,
        AlunoRepository $aluno)
    {
        $this->model = $matricula;
        $this->moduloDisciplinaRepository = $modulo;
        $this->ofertaDisciplinaRepository = $oferta;
        $this->alunoRepository = $aluno;
    }

    public function findBy(array $options, array $select = null, array $order = null)
    {
        $query = $this->model
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->join('acd_modulos_matrizes', function ($join) {
                $join->on('mdc_mdo_id', '=', 'mdo_id');
            })
            ->join('acd_disciplinas', function ($join) {
                $join->on('mdc_dis_id', '=', 'dis_id');
            })
            ->join('acd_professores', function ($join) {
                $join->on('ofd_prf_id', '=', 'prf_id');
            })
            ->join('gra_pessoas', function ($join) {
                $join->on('prf_pes_id', '=', 'pes_id');
            });

        foreach ($options as $option) {
            if (gettype($option[2]) == 'array') {
                $function = 'whereIn';

                if ($option[1] == '<>') {
                    $function = 'whereNotIn';
                }

                $query = $query->$function($option[0], $option[2]);
                continue;
            }

            $query = $query->where($option[0], $option[1], $option[2]);
        }

        if (!is_null($select)) {
            $query = $query->select($select);
        }

        if (!is_null($order)) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        return $query->get();
    }

    public function getAllMatriculasByAluno($alunoId)
    {
        $query = $this->model
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->join('acd_modulos_matrizes', function ($join) {
                $join->on('mdc_mdo_id', '=', 'mdo_id');
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
            ->where('mat_alu_id', '=', $alunoId)
            ->orderBy('mdo_id')
            ->get();

        return $query;
    }

    public function getAllMatriculasByAlunoModuloMatriz($alunoId, $moduloId)
    {
        $query = $this->model
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->join('acd_modulos_matrizes', function ($join) {
                $join->on('mdc_mdo_id', '=', 'mdo_id');
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
            ->where('mat_alu_id', '=', $alunoId)
            ->where('mdc_mdo_id', '=', $moduloId)
            ->get();

        return $query;
    }

    public function getDisciplinasCursadasByAluno($alunoId, $options = null)
    {
        $query = $this->model
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
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
            ->where('mat_alu_id', '=', $alunoId);


        if (!is_null($options)) {
            foreach ($options as $key => $value) {
                if ($key == 'mof_situacao_matricula') {
                    $query = $query->whereIn($key, $value);
                    continue;
                }

                $query = $query->where($key, '=', $value);
            }
        }

        $disciplinas = $query->get();

        if ($disciplinas->count()) {
            for ($i=0;$i<$disciplinas->count();$i++) {
                $quantMatriculas = $this->model
                                        ->where('mof_ofd_id', '=', $disciplinas[$i]->ofd_id)
                                        ->where('mof_situacao_matricula', '=', 'cursando')
                                        ->count();
                $disciplinas[$i]->quant_matriculas = $quantMatriculas;
            }
        }
        return $disciplinas;
    }

    public function getDisciplinasOfertadasNotCursadasByAluno($alunoId, $turmaId, $periodoId)
    {
        // pega as disciplinas cursadas pelo aluno

        $disciplinasCursadas = $this->getDisciplinasCursadasByAluno($alunoId, [
            'ofd_per_id' => $periodoId,
            'ofd_trm_id' => $turmaId,
            'mof_situacao_matricula' => ['cursando', 'aprovado_media', 'aprovado_final'],
        ])->pluck('mof_ofd_id')->toArray();

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
                    ->select(
                        'ofd_id',
                        'dis_nome',
                        'dis_creditos',
                        'dis_carga_horaria',
                        'ofd_qtd_vagas',
                        'pes_nome'
                    )
                    ->where('ofd_per_id', '=', $periodoId)
                    ->where('ofd_trm_id', '=', $turmaId);

        if (!empty($disciplinasCursadas)) {
            $query = $query->whereNotIn('ofd_id', $disciplinasCursadas);
        } else {
            $query = $query->whereNotIn('ofd_id', [0]);
        }

        $disciplinasOfertadas =  $query->get();

        // pega a matricula do aluno
        $aluno = $this->alunoRepository->find($alunoId);
        $matriculaAluno = $aluno->matriculas()->where('mat_trm_id', $turmaId)->first();

        if ($disciplinasOfertadas->count()) {
            for ($i=0;$i<$disciplinasOfertadas->count();$i++) {
                $quantMatriculas = $this->model
                                        ->where('mof_ofd_id', '=', $disciplinasOfertadas[$i]->ofd_id)
                                        ->where('mof_situacao_matricula', '=', 'cursando')
                                        ->count();

                $disciplinasOfertadas[$i]->quant_matriculas = $quantMatriculas;

                //Status 1 vagas disponiveis, status 2 sem pré requisitos satisfeitos, status 0 sem vagas disponiveis
                $disciplinasOfertadas[$i]->status = 1;

                if (!$this->verifyIfAlunoAprovadoPreRequisitos($matriculaAluno->mat_id, $disciplinasOfertadas[$i]->ofd_id)) {
                    $disciplinasOfertadas[$i]->status = 2;
                }

                if ($quantMatriculas >= $disciplinasOfertadas[$i]->ofd_qtd_vagas) {
                    $disciplinasOfertadas[$i]->status = 0;
                }
            }
        }

        return $disciplinasOfertadas;
    }

    public function verifyMatriculaDisciplina($matriculaId, $ofertaId)
    {
        $query = $this->model->where('mof_ofd_id', '=', $ofertaId)
                             ->where('mof_mat_id', '=', $matriculaId)
                             ->whereNotIn('mof_situacao_matricula', ['cancelado', 'reprovado_media', 'reprovado_final']);

        return $query->first();
    }

    public function verifyQtdVagas($ofertaId)
    {
        $query = $this->model
                    ->join('acd_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
                    ->where('mof_ofd_id', '=', $ofertaId)
                    ->where('mof_situacao_matricula', '=', 'cursando')
                    ->get();

        if ($query->count()) {
            $vagas = $query[0]->ofd_qtd_vagas;
            $qtd = $query->count();

            if (($vagas == $qtd)) {
                return false;
            }
        }

        return true;
    }

    public function getQuantMatriculasByOfertaDisciplina($ofertaId)
    {
        return $this->model->where('mof_ofd_id', '=', $ofertaId)
                            ->whereNotIn('mof_situacao_matricula', ['cancelado'])
                            ->count();
    }
    
    public function verifyIfAlunoAprovadoPreRequisitos($matriculaId, $ofertaDisciplinaId)
    {
        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($ofertaDisciplinaId);

        $preRequisitos = $this->moduloDisciplinaRepository->getDisciplinasPreRequisitos($ofertaDisciplina->ofd_mdc_id);


        if (!empty($preRequisitos)) {
            $quantAprovadas = 0;

            foreach ($preRequisitos as $req) {
                // busca a oferta de disciplina
                $oferta = $this->ofertaDisciplinaRepository->findAll(['ofd_mdc_id' => $req->mdc_id])->first();

                // busca a matricula do aluno nessa disciplina
                $matriculaOferta = $this->findBy([
                    ['mof_mat_id', '=', $matriculaId],
                    ['mof_ofd_id', '=', $oferta->ofd_id]
                ])->first();

                if ($matriculaOferta) {
                    if (in_array($matriculaOferta->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                        $quantAprovadas++;
                    }
                }
            }

            if ($quantAprovadas < count($preRequisitos)) {
                return false;
            }
        }

        return true;
    }

    public function createMatricula(array $data)
    {
        $ofertaId = $data['ofd_id'];

        // verifica se a disciplina possui vagas disponiveis
        if (!($this->verifyQtdVagas($ofertaId))) {
            return array("type" => "error", "message" => "Sem vagas disponiveis");
        }

        // verifica se já existe uma matricula ativa nessa oferta de disciplina
        $matriculaExists = $this->verifyMatriculaDisciplina($data['mat_id'], $data['ofd_id']);

        if (!is_null($matriculaExists)) {
            if ($matriculaExists['mof_situacao_matricula'] == 'aprovado_media' || 'aprovado_final') {
                return array("type" => "error", "message" => "Aluno já aprovado nessa disciplina.");
            } elseif ($matriculaExists['mof_situacao_matricula'] == 'cursando') {
                return array("type" => "error", "message" => "Aluno já matriculado nessa disciplina para esse periodo e turma");
            }
        }

        // verifica se o aluno está aprovado nas disciplinas pre-requisitos, caso existam
        $aprovadoPreRequisitos = $this->verifyIfAlunoAprovadoPreRequisitos($data['mat_id'], $data['ofd_id']);

        if (!$aprovadoPreRequisitos) {
            return array("type" => "error", "message" => "Aluno possui pre-requisitos não satisfeitos");
        }

        $obj = $this->create([
            'mof_mat_id' => $data['mat_id'],
            'mof_ofd_id' => $data['ofd_id'],
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'cursando'
        ]);

        return array('type' => 'success', 'message' => 'Aluno matriculado com sucesso!', 'obj' => $obj);
    }

    private function getMatriculaByAlunoDisciplina($matriculaId, $ofertaId)
    {
        return $this->model
                    ->where('mof_mat_id', $matriculaId)
                    ->where('mof_ofd_id', $ofertaId)
                    ->whereNotIn('mof_situacao_matricula', ['cancelado', 'reprovado_media', 'reprovado_final'])
                    ->orderBy('mof_id', 'desc')
                    ->first();
    }

    public function getAlunosMatriculasLote($turmaId, $ofertaId)
    {
        $alunos = DB::table('acd_matriculas')
                    ->join('acd_alunos', 'mat_alu_id', 'alu_id')
                    ->join('gra_pessoas', 'alu_pes_id', 'pes_id')
                    ->select('mat_id', 'pes_id', 'pes_nome')
                    ->where('mat_trm_id', $turmaId)
                    ->orderBy('pes_nome', 'asc')
                    ->get();

        if ($alunos->count()) {
            foreach ($alunos as $key => $aluno) {
                $matricula = $this->getMatriculaByAlunoDisciplina($aluno->mat_id, $ofertaId);

                $alunos[$key]->mof_situacao_matricula = null;

                if (!is_null($matricula)) {
                    $alunos[$key]->mof_situacao_matricula = $matricula->mof_situacao_matricula;
                    continue;
                }

                if (!$this->verifyIfAlunoAprovadoPreRequisitos($aluno->mat_id, $ofertaId)) {
                    $alunos[$key]->mof_situacao_matricula = 'no_pre_requisitos';
                }
            }
        }

        return $alunos;
    }

    public function getAllAlunosBySituacao($turmaId, $ofertaId, $situacao)
    {
        $query = $this->model
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })
            ->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })
            ->select('mat_id', 'pes_nome', 'mof_situacao_matricula', 'trm_nome', 'pol_nome', 'pes_email')
            ->where('mof_ofd_id', '=', $ofertaId)
            ->where('mat_trm_id', $turmaId)
            ->orderBy('pes_nome', 'asc')
            ->get();

        if ($situacao != null) {
            $query = $query->where('mof_situacao_matricula', $situacao);
        }

        return $query;
    }

    /**
     * PaginateRequest
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByParametros(array $requestParameters = null)
    {
        $sort = array();
        if (empty($requestParameters)) {
            return new Collection();
        }

        if (empty($requestParameters['ofd_id']) || empty($requestParameters['trm_id'])) {
            return new Collection();
        }

        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            $query =  $this->model
                ->join('acd_matriculas', function ($join) {
                    $join->on('mof_mat_id', '=', 'mat_id');
                })
                ->join('acd_turmas', function ($join) {
                    $join->on('mat_trm_id', '=', 'trm_id');
                })
                ->join('acd_polos', function ($join) {
                    $join->on('mat_pol_id', '=', 'pol_id');
                })
                ->join('acd_alunos', function ($join) {
                    $join->on('mat_alu_id', '=', 'alu_id');
                })
                ->join('gra_pessoas', function ($join) {
                    $join->on('alu_pes_id', '=', 'pes_id');
                })
                ->select('mat_id', 'pes_nome', 'mof_situacao_matricula', 'trm_nome', 'pol_nome', 'pes_email')
                ->where('mof_ofd_id', '=', $requestParameters['ofd_id'])
                ->where('mat_trm_id', $requestParameters['trm_id'])
                ->orderBy($sort['field'], $sort['sort']);

            if ($requestParameters['mof_situacao_matricula'] != null) {
                $query = $query->where('mof_situacao_matricula', $requestParameters['mof_situacao_matricula']);
            }

            return $query->paginate(15);
        }

        $dados =  $this->model
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })
            ->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })
            ->select('mat_id', 'pes_nome', 'mof_situacao_matricula', 'trm_nome', 'pol_nome', 'pes_email')
            ->where('mof_ofd_id', '=', $requestParameters['ofd_id'])
            ->where('mat_trm_id', $requestParameters['trm_id']);

        if ($requestParameters['mof_situacao_matricula'] != null) {
            $dados = $dados->where('mof_situacao_matricula', $requestParameters['mof_situacao_matricula']);
        }


        return $dados->paginate(15);
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
                        ->join('acd_matriculas', function ($join) {
                            $join->on('mof_mat_id', '=', 'mat_id');
                        })
                        ->join('acd_alunos', function ($join) {
                            $join->on('mat_alu_id', '=', 'alu_id');
                        })
                        ->join('gra_pessoas', function ($join) {
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
