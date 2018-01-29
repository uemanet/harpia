<?php

namespace Modulos\Academico\Repositories;

use function foo\func;
use Illuminate\Support\Collection;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Core\Repository\BaseRepository;
use DB;

class MatriculaOfertaDisciplinaRepository extends BaseRepository
{
    protected $moduloDisciplinaRepository;
    protected $ofertaDisciplinaRepository;
    protected $turmaRepository;
    private $alunoRepository;

    public function __construct(
        MatriculaOfertaDisciplina $matricula,
        ModuloDisciplinaRepository $modulo,
        OfertaDisciplinaRepository $oferta,
        TurmaRepository $turmaRepository,
        AlunoRepository $aluno)
    {
        $this->model = $matricula;
        $this->moduloDisciplinaRepository = $modulo;
        $this->ofertaDisciplinaRepository = $oferta;
        $this->turmaRepository = $turmaRepository;
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

    public function getMatriculasOfertasDisciplinasByMatricula($matriculaId, array $options)
    {
        // buscar as ofertas de disciplinas
        $ofertasDisciplinas = $this->ofertaDisciplinaRepository->findAll($options, ['ofd_id']);

        $matriculas = [];
        foreach ($ofertasDisciplinas as $oferta) {

            // busca sempre a ultima matricula do aluno na oferta de disciplina
            $result = $this->model
                ->join('acd_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
                ->join('acd_modulos_disciplinas', 'ofd_mdc_id', '=', 'mdc_id')
                ->join('acd_disciplinas', 'mdc_dis_id', '=', 'dis_id')
                ->where('mof_ofd_id', '=', $oferta->ofd_id)
                ->where('mof_mat_id', '=', $matriculaId)
                ->orderBy('mof_id', 'desc')
                ->first();

            if ($result) {
                $matriculas[] = $result;
            }
        }

        return $matriculas;
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
            for ($i = 0; $i < $disciplinas->count(); $i++) {
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
        // busca as disciplinas ofertadas para a turma e periodo
        $ofertasDisciplinas = $this->ofertaDisciplinaRepository->findAll([
            'ofd_trm_id' => $turmaId, 'ofd_per_id' => $periodoId]);

        // busca o aluno
        $aluno = $this->alunoRepository->find($alunoId);

        // busca a matricula do aluno na turma
        $matricula = $aluno->matriculas()->where('mat_trm_id', '=', $turmaId)->first();

        $naomatriculadas = [];

        foreach ($ofertasDisciplinas as $ofertasDisciplina) {
            $ofd_id = $ofertasDisciplina->ofd_id;
            $status = 1;

            // 1º Passo - Verifica se o aluno possui matricula na disciplina da matriz
            $matriculaOfertaDisciplina = $this->getLastMatriculaDisciplina($matricula->mat_id, $ofertasDisciplina->ofd_mdc_id);

            if ($matriculaOfertaDisciplina) {

                $cancelado = $matriculaOfertaDisciplina->mof_situacao_matricula == 'cancelado';
                $reprovado = in_array($matriculaOfertaDisciplina->mof_situacao_matricula, ['reprovado_media', 'reprovado_final']) && ($matriculaOfertaDisciplina->mof_ofd_id != $ofd_id);

                if ($cancelado || $reprovado) {
                    // Se o aluno não satisfazer os pre-requisitos, seta o status com valor 2
                    if (!$this->verifyIfAlunoAprovadoPreRequisitos($matricula->mat_id, $ofd_id)) {
                        $status = 2;
                    }

                    // Se a disciplina não possuir vagas disponiveis, setar o status zero
                    if (!$this->verifyHaveVagas($ofd_id)) {
                        $status = 0;
                    }

                    $ofertasDisciplina->status = $status;
                    $ofertasDisciplina->quant_matriculas = $this->getQuantMatriculasByOfertaDisciplina($ofd_id);
                    $naomatriculadas[] = $ofertasDisciplina;
                    continue;
                }

                if (in_array($matriculaOfertaDisciplina->mof_situacao_matricula, ['cursando', 'aprovado_media', 'aprovado_final'])) {
                    continue;
                }
            }

            // 2º Passo - Caso o aluno não possui matricula na disciplina da matriz, disponibilizar a matricula do mesmo
            $status = 1;

            // Se o aluno não satisfazer os pre-requisitos, seta o status com valor 2
            if (!$this->verifyIfAlunoAprovadoPreRequisitos($matricula->mat_id, $ofd_id)) {
                $status = 2;
            }

            // Se a disciplina não possuir vagas disponiveis, setar o status zero
            if (!$this->verifyHaveVagas($ofd_id)) {
                $status = 0;
            }

            $ofertasDisciplina->status = $status;
            $ofertasDisciplina->quant_matriculas = $this->getQuantMatriculasByOfertaDisciplina($ofd_id);
            $naomatriculadas[] = $ofertasDisciplina;
        }

        return $naomatriculadas;
    }

    public function getLastMatriculaDisciplina($matriculaId, $moduloDisciplinaId)
    {
        $query = $this->model->join('acd_ofertas_disciplinas', 'mof_ofd_id', 'ofd_id')
            ->where('ofd_mdc_id', '=', $moduloDisciplinaId)
            ->where('mof_mat_id', '=', $matriculaId)
            ->orderBy('mof_id', 'desc');

        return $query->first();
    }

    public function verifyHaveVagas($ofertaId)
    {

        $oferta = DB::table('acd_ofertas_disciplinas')
            ->where('ofd_id', '=', $ofertaId)
            ->first();

        $query = $this->model
            ->join('acd_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
            ->where('mof_ofd_id', '=', $ofertaId)
            ->where('mof_situacao_matricula', '<>', 'cancelado')
            ->get();


        if ($query->count() >= $oferta->ofd_qtd_vagas) {
            return false;
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
                ], null, ['mof_id' => 'desc'])->first();

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
        // verifica se o aluno ainda está cursando o curso
        $matricula = Matricula::find($data['mat_id']);

        if ($matricula->mat_situacao != 'cursando') {
            return array("type" => "error", "message" => "Aluno não está cursando o curso");
        }

        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($data['ofd_id']);

        // verifica se a disciplina possui vagas disponiveis
        if (!($this->verifyHaveVagas($ofertaDisciplina->ofd_id))) {
            return array("type" => "error", "message" => "Sem vagas disponiveis");
        }

        // busca a ultima matricula do aluno na disciplina da matriz
        $matriculaExists = $this->getLastMatriculaDisciplina($data['mat_id'], $ofertaDisciplina->ofd_mdc_id);

        if ($matriculaExists) {
            if (in_array($matriculaExists->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                return array("type" => "error", "message" => "Aluno já aprovado nessa disciplina.");
            } elseif ($matriculaExists->mof_situacao_matricula == 'cursando') {
                return array("type" => "error", "message" => "Aluno está cursando essa disciplina");
            } elseif (in_array($matriculaExists->mof_situacao_matricula, ['reprovado_media', 'reprovado_final']) &&
                $matriculaExists->ofd_id == $ofertaDisciplina->ofd_id) {
                return array("type" => "error", "message" => "Aluno está reprovado nesta oferta de disciplina");
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

    public function getAlunosMatriculasLote(array $parameters)
    {
        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($parameters['ofd_id']);

        $poloId = (isset($parameters['pol_id'])) ? $parameters['pol_id'] : null;

        $query = Matricula::join('acd_alunos', 'mat_alu_id', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', 'pes_id')
            ->where('mat_trm_id', $parameters['trm_id'])
            ->where('mat_situacao', 'cursando');

        if ($poloId) {
            $query = $query->where('mat_pol_id', $poloId);
        }

        $matriculas = $query->select('acd_matriculas.*')
            ->orderBy('pes_nome', 'asc')->get();

        $naoMatriculados = [];
        $cursando = [];
        $aprovados = [];
        $reprovados = [];

        foreach ($matriculas as $matricula) {

            // verifica se o aluno possui alguma matricula nesta disciplina da matriz
            $matriculaDisciplina = $this->getLastMatriculaDisciplina($matricula->mat_id, $ofertaDisciplina->ofd_mdc_id);

            if ($matriculaDisciplina) {
                if ($matriculaDisciplina->mof_situacao_matricula == 'cancelado') {
                    $status = 'apto';

                    if (!$this->verifyIfAlunoAprovadoPreRequisitos($matricula->mat_id, $ofertaDisciplina->ofd_id)) {
                        $status = 'no_pre_requisitos';
                    }

                    $matricula->status = $status;
                    $naoMatriculados[] = $matricula;
                    continue;
                }

                if ($matriculaDisciplina->mof_situacao_matricula == 'cursando') {
                    if ($matriculaDisciplina->ofd_id == $ofertaDisciplina->ofd_id) {
                        $cursando[] = $matricula;
                    }
                    continue;
                }

                if (in_array($matriculaDisciplina->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                    if ($matriculaDisciplina->ofd_id == $ofertaDisciplina->ofd_id) {
                        $aprovados[] = $matricula;
                    }
                    continue;
                }

                if (in_array($matriculaDisciplina->mof_situacao_matricula, ['reprovado_media', 'reprovado_final'])) {
                    if ($matriculaDisciplina->ofd_id == $ofertaDisciplina->ofd_id) {
                        $reprovados[] = $matricula;
                    }
                    continue;
                }
            }

            $status = 'apto';

            if (!$this->verifyIfAlunoAprovadoPreRequisitos($matricula->mat_id, $ofertaDisciplina->ofd_id)) {
                $status = 'no_pre_requisitos';
            }

            $matricula->status = $status;
            $naoMatriculados[] = $matricula;
        }

        return [
            'nao_matriculados' => $naoMatriculados,
            'cursando' => $cursando,
            'aprovados' => $aprovados,
            'reprovados' => $reprovados
        ];
    }

    public function getAllAlunosBySituacao($turmaId, $ofertaId, $situacao, $poloId)
    {
        $matriculas = $this->model
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
            ->where('mof_ofd_id', '=', $ofertaId)
            ->where('mat_trm_id', $turmaId)
            ->orderBy('pes_nome', 'asc')
            ->get();

        if ($poloId != null) {
            $matriculas = $matriculas->where('mat_pol_id', $poloId);
        }

        if ($situacao != null) {
            $matriculas = $matriculas->where('mof_situacao_matricula', $situacao);
        }

        foreach ($matriculas as $key => $matricula) {
            $rg = DB::table('gra_documentos')
                ->where('doc_pes_id', '=', $matricula->pes_id)
                ->where('doc_tpd_id', 1)
                ->first();

            if ($rg) {
                $matricula['rg'] = $rg->doc_conteudo;
            } else {
                $matricula['rg'] = null;
            }

            $cpf = DB::table('gra_documentos')
                ->where('doc_pes_id', '=', $matricula->pes_id)
                ->where('doc_tpd_id', 2)
                ->first();

            if ($rg) {
                $matricula['cpf'] = $cpf->doc_conteudo;
            } else {
                $matricula['cpf'] = null;
            }

            $matricula->pes_nascimento = date("d/m/Y", strtotime($matricula->pes_nascimento));
        }

        return $matriculas;
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

        $query = $this->model
            ->join('acd_matriculas', 'mof_mat_id', '=', 'mat_id')
            ->join('acd_turmas', 'mat_trm_id', '=', 'trm_id')
            ->join('acd_polos', 'mat_pol_id', '=', 'pol_id')
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->select('mat_id', 'pes_nome', 'mof_situacao_matricula', 'trm_nome', 'pol_nome', 'pes_email', 'mat_pol_id')
            ->where('mof_ofd_id', '=', $requestParameters['ofd_id'])
            ->where('mat_trm_id', $requestParameters['trm_id']);

        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            $query = $query->orderBy($sort['field'], $sort['sort']);
        }

        if ($requestParameters['mof_situacao_matricula'] != null) {
            $query = $query->where('mof_situacao_matricula', $requestParameters['mof_situacao_matricula']);
        }

        if (array_key_exists('pol_id', $requestParameters)) {
            if ($requestParameters['pol_id']) {
                $query = $query->where('mat_pol_id', $requestParameters['pol_id']);
            }
        }


        return $query->paginate(15);
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

    public function update(array $data, $id, $attribute = null)
    {
        $matricula = $this->model->find($id);

        $configsCurso = $matricula->ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

        $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
            return [$item->cfc_nome => $item->cfc_valor];
        })->toArray();

        $dados = $this->calculaNotas($data, $configuracoesCurso);
        return parent::update($dados, $id, $attribute);
    }

    private function calculaNotas(array $data, array $configuracoesCurso)
    {
        if (isset($data['mof_final']) && ($data['mof_final'] == '' || $data['mof_final'] == null)) {
            unset($data['mof_final']);
        }

        if (isset($data['mof_final']) && ($data['mof_recuperacao'] == '' || $data['mof_recuperacao'] == null)) {
            unset($data['mof_recuperacao']);
        }

        // Se a disciplina for avaliada por conceito
        if (isset($data['mof_conceito']) && !is_null($data['mof_conceito'])) {
            $data['mof_situacao_matricula'] = 'reprovado_media';

            if (str_contains($configuracoesCurso['conceitos_aprovacao'], $data['mof_conceito'])) {
                $data['mof_situacao_matricula'] = 'aprovado_media';
            }

            return $data;
        }

        $somaNotas = (float)$data['mof_nota1'] + (float)$data['mof_nota2'] + (float)$data['mof_nota3'];
        $menorNota = min((float)$data['mof_nota1'], (float)$data['mof_nota2'], (float)$data['mof_nota3']);

        $media = $somaNotas / 3;
        $data['mof_mediafinal'] = $media;
        $data['mof_situacao_matricula'] = 'aprovado_media';

        // Recuperacao
        if ($media < (float)$configuracoesCurso['media_min_aprovacao'] && isset($data['mof_recuperacao'])) {
            if ($configuracoesCurso['modo_recuperacao'] == 'substituir_media_final' && (float)$data['mof_recuperacao'] > $media) {
                $data['mof_mediafinal'] = (float)$data['mof_recuperacao'];
                $media = (float)$data['mof_recuperacao'];
            }

            if ($configuracoesCurso['modo_recuperacao'] == 'substituir_menor_nota') {
                $media = (($somaNotas - $menorNota) + (float)$data['mof_recuperacao']) / 3;
                $data['mof_mediafinal'] = $media;
            }

            $data['mof_situacao_matricula'] = 'aprovado_media';

            if ($data['mof_mediafinal'] < (float)$configuracoesCurso['media_min_aprovacao']) {
                $data['mof_situacao_matricula'] = 'reprovado_media';
            }
        }

        // Final
        if ($media < (float)$configuracoesCurso['media_min_aprovacao'] && isset($data['mof_final'])) {
            $media = ($media + (float)$data['mof_final']) / 2;

            $data['mof_mediafinal'] = $media;
            $data['mof_situacao_matricula'] = 'reprovado_final';

            if ($media >= (float)$configuracoesCurso['media_min_aprovacao_final']) {
                $data['mof_situacao_matricula'] = 'aprovado_final';
            }
        }

        $data['mof_mediafinal'] = (float)number_format($data['mof_mediafinal'], 3);
        return $data;
    }
}
