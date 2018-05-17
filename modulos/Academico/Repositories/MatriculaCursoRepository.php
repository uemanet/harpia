<?php
declare(strict_types=1);

namespace Modulos\Academico\Repositories;

use DB;
use Auth;
use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class MatriculaCursoRepository extends BaseRepository
{
    protected $ofertaCursoRepository;
    protected $matrizCurricularRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $moduloMatrizRepository;
    protected $turmaRepository;
    protected $registroRepository;
    protected $vinculoRepository;

    private $meses = [
        1 => "Jan",
        2 => "Fev",
        3 => "Mar",
        4 => "Abr",
        5 => "Mai",
        6 => "Jun",
        7 => "Jul",
        8 => "Ago",
        9 => "Set",
        10 => "Out",
        11 => "Nov",
        12 => "Dez",
    ];

    public function __construct(
        Matricula $matricula,
        OfertaCursoRepository $oferta,
        MatrizCurricularRepository $matriz,
        MatriculaOfertaDisciplinaRepository $matriculaOferta,
        ModuloMatrizRepository $modulo, TurmaRepository $turmaRepository,
        RegistroRepository $registroRepository,
        VinculoRepository $vinculoRepository
    ) {
        $this->model = $matricula;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $oferta;
        $this->matrizCurricularRepository = $matriz;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOferta;
        $this->moduloMatrizRepository = $modulo;
        $this->registroRepository = $registroRepository;
        $this->vinculoRepository = $vinculoRepository;
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

        return (bool)$result->count();
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
            ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente', 'reprovado'])
            ->where('ofc_crs_id', '=', $cursoId)
            ->get();

        return (bool)$result->count();
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
                $join->on('ofc_crs_id', '=', 'crs_id')->where('crs_nvc_id', '=', '1');
            })
            ->where('mat_alu_id', '=', $alunoId)
            ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente'])
            ->get();

        return (bool)$result->count();
    }

    public function verifyExistsVagasByTurma($turmaId)
    {
        $result = DB::table('acd_turmas')
            ->leftJoin('acd_matriculas', 'mat_trm_id', '=', 'trm_id')
            ->select('trm_qtd_vagas', DB::raw('COUNT(mat_trm_id) as qtd_matriculas'))
            ->where('trm_id', '=', $turmaId)
            ->groupBy('trm_id')
            ->first();

        return ($result && $result->qtd_matriculas < $result->trm_qtd_vagas);
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

        if ($this->verifyIfExistsMatriculaInCursoGraducao($alunoId)) {
            $query = $query->where('crs_nvc_id', '<>', 1);
        }

        return $query->pluck('crs_nome', 'crs_id');
    }

    public function findAll(array $options, array $select = null, array $order = null)
    {
        $query = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->leftJoin('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->leftJoin('acd_grupos', function ($join) {
                $join->on('mat_grp_id', '=', 'grp_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            });

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
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

    public function findAllVinculo(array $options, array $select = null, array $order = null)
    {
        $query = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->leftJoin('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->leftJoin('acd_grupos', function ($join) {
                $join->on('mat_grp_id', '=', 'grp_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })->whereIn('ofc_crs_id', $this->vinculoRepository->getCursos(Auth::user()->usr_id));

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
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

    public function createMatricula($alunoId, array $options)
    {
        try {
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
            if ($curso->crs_nvc_id == 1) {
                if ($this->verifyIfExistsMatriculaInCursoGraducao($alunoId)) {
                    return array(
                        'type' => 'error',
                        'message' => 'Aluno já possui matricula ativa em outro curso de graduação'
                    );
                }
            }

            // verifica se a turma ainda possui vagas disponiveis
            if (!$this->verifyExistsVagasByTurma($options['mat_trm_id'])) {
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

            $matricula = $this->create($dataMatricula);

            if ($matricula) {
                return array(
                    'type' => 'success',
                    'message' => 'Matricula efetuada com sucesso!',
                    'matricula' => $matricula
                );
            }
        } catch (\Exception $exception) {
            if (env('APP_DEBUG')) {
                throw $exception;
            }
        }

        return array(
            'type' => 'error',
            'message' => 'Erro ao tentar matricular aluno'
        );
    }

    public function findMatriculaIdByTurmaAluno($alunoId, $turmaId)
    {
        $matriculadoemtcc = null;

        $matricula = DB::table('acd_matriculas')
            ->where('mat_trm_id', '=', $turmaId)
            ->where('mat_alu_id', '=', $alunoId)
            ->first();

        if ($matricula != null) {
            $matriculadoemtcc = DB::table('acd_modulos_disciplinas')
                ->join('acd_ofertas_disciplinas', 'ofd_mdc_id', '=', 'mdc_id')
                ->join('acd_matriculas_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
                ->where('mdc_tipo_disciplina', '=', 'tcc')
                ->where('mof_mat_id', '=', $matricula->mat_id)->first();
        }

        if ($matriculadoemtcc === null) {
            return null;
        }

        return $matricula;
    }

    public function findDadosByTurmaId($turmaId)
    {
        $dados = DB::table('acd_matriculas_ofertas_disciplinas')
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->leftJoin('acd_lancamentos_tccs', 'ltc_mof_id', '=', 'mof_id')
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->where('mat_trm_id', '=', $turmaId)
            ->orderBy('pes_nome', 'asc')
            ->get();


        return $dados;
    }

    public function getAlunosAptosOrNot($turmaId, $poloId)
    {
        // busca todas as matriculas da turma
        $matriculas = $this->findAll(['mat_trm_id' => $turmaId, 'mat_pol_id' => $poloId], null, ['pes_nome' => 'asc']);

        $result = [];

        if ($matriculas->count()) {
            foreach ($matriculas as $matricula) {
                $obj = new \stdClass();

                $obj->mat_id = $matricula->mat_id;
                $obj->alu_id = $matricula->alu_id;
                $obj->pes_nome = $matricula->pes_nome;

                if ($matricula->mat_situacao == 'concluido') {
                    $obj->status = [
                        'status' => 'info',
                        'message' => 'Concluído',
                        'data_conclusao' => $matricula->mat_data_conclusao
                    ];

                    $result[] = $obj;
                    continue;
                }

                if ($matricula->mat_situacao == 'cursando') {
                    $obj->status = $this->verifyIfAlunoIsAptoOrNot($matricula->mat_id);
                    $result[] = $obj;
                    continue;
                }

                if ($matricula->mat_situacao == 'reprovado') {
                    $obj->status = [
                        'status' => 'danger',
                        'message' => 'Reprovado',
                    ];
                    $result[] = $obj;
                    continue;
                }

                $obj->status = [
                    'status' => 'warning',
                    'message' => ucfirst($matricula->mat_situacao)
                ];
                $result[] = $obj;
            }
        }

        return $result;
    }

    private function verifyIfAlunoIsAptoOrNot($matriculaId)
    {
        $matricula = $this->find($matriculaId);
        $curso = $matricula->turma->ofertacurso->curso;

        // 1º Regra - Aprovação em todas as disciplinas Obrigatórias
        $aprovacao = $this->verifyIfAlunoIsAprovadoDisciplinasObrigatorias($matricula);
        if (!$aprovacao) {
            return array('status' => 'warning', 'message' => 'Não possui aprovação em todas as disciplinas obrigatórias');
        }

        // 2º Regra - Carga Horária/Créditos nas eletivas por Módulo
        $aprovacao = $this->verifyIfAlunoIsAprovadoEletivasModulosMatriz($matricula);
        if (!$aprovacao) {
            return array('status' => 'warning', 'message' => 'Aluno não atingiu carga horária/creditos minima em algum modulo da matriz curricular');
        }

        // 3º Regra - Carga Horária Total do Curso
        $aprovacao = $this->verifyIfAlunoHaveCargaHorariaMinCurso($matricula);
        if (!$aprovacao) {
            return array('status' => 'warning', 'message' => 'Aluno não atingiu carga horária minima do curso');
        }

        // A 4º e 5º regra nao se aplicam aos cursos tecnicos
        if (!in_array($curso->crs_nvc_id, [2,7])) {
            // 4º Regra - Aprovação Tcc
            $aprovacao = $this->verifyIfAlunoAprovadoTcc($matricula);
            if (!$aprovacao) {
                return array('status' => 'warning', 'message' => 'Aluno não possui aprovação na disciplina de TCC');
            }

            // 5º Regra - Verificar se aluno possui Tcc lançado
            $aprovacao = $this->verifyIfAlunoHaveTccLancado($matricula);
            if (!$aprovacao) {
                return array('status' => 'warning', 'message' => 'Aluno não possui TCC lançado');
            }
        }

        // 6º Regra - Especifica para cursos de especialização
        // Verifica se o aluno possui uma titulação de Graduacao cadastrada no sistema
        if ($curso->crs_nvc_id == 4) {
            $aprovacao = $this->verifyIfAlunoHaveTitulacaoGraduacao($matricula);
            if (!$aprovacao) {
                return array('status' => 'warning', 'message' => 'Aluno não possui titulação de graduação cadastrada');
            }
        }

        return array('status' => 'success', 'message' => 'Apto');
    }

    private function verifyIfAlunoIsAprovadoDisciplinasObrigatorias(Matricula $matricula)
    {
        // busca a matriz curricular do curso
        $matrizCurricular = $matricula->turma->ofertacurso->matriz;

        // busca todas as disciplinas obrigatorias da matriz do curso
        $disciplinasObrigatoriasMatriz = $this->matrizCurricularRepository
            ->getDisciplinasByMatrizId($matrizCurricular->mtc_id, ['mdc_tipo_disciplina' => 'obrigatoria'])
            ->pluck('mdc_id')
            ->toArray();

        $matriculasAluno = $this->matriculaOfertaDisciplinaRepository
            ->getMatriculasOfertasDisciplinasByMatricula($matricula->mat_id,
                ['mdc_tipo_disciplina' => 'obrigatoria', 'ofd_trm_id' => $matricula->mat_trm_id]);

        $quantDisciplinasObrigatoriasAprovadas = 0;
        foreach ($matriculasAluno as $matriculaOferta) {
            if (in_array($matriculaOferta->mdc_id, $disciplinasObrigatoriasMatriz)
                && in_array($matriculaOferta->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                $quantDisciplinasObrigatoriasAprovadas += 1;
            }
        }

        if ($quantDisciplinasObrigatoriasAprovadas == count($disciplinasObrigatoriasMatriz)) {
            return true;
        }

        return false;
    }

    private function verifyIfAlunoIsAprovadoEletivasModulosMatriz(Matricula $matricula)
    {
        // busca a matriz curricular do curso
        $matrizCurricular = $matricula->turma->ofertacurso->matriz;

        //busca os modulos da matriz
        $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($matrizCurricular->mtc_id);

        foreach ($modulos as $modulo) {

            // busca todas as disciplinas eletivas do modulo da matriz
            $disciplinasEletivasMatriz = $this->matrizCurricularRepository
                ->getDisciplinasByMatrizId($matrizCurricular->mtc_id,
                    ['mdc_tipo_disciplina' => 'eletiva', 'mdc_mdo_id' => $modulo->mdo_id])
                ->pluck('mdc_id')
                ->toArray();

            if (empty($disciplinasEletivasMatriz)) {
                continue;
            }

            $cargaHorariaEletivas = 0;
            $creditosEletivas = 0;
            foreach ($disciplinasEletivasMatriz as $disciplinaId) {
                $matriculaOferta = $this->matriculaOfertaDisciplinaRepository->getMatriculasOfertasDisciplinasByMatricula($matricula->mat_id,
                    ['ofd_mdc_id' => $disciplinaId, 'ofd_trm_id' => $matricula->mat_trm_id])[0];

                if ($matriculaOferta && in_array($matriculaOferta->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                    $cargaHorariaEletivas += $matriculaOferta->dis_carga_horaria;
                    $creditosEletivas += $matriculaOferta->dis_creditos;
                }
            }

            // se o aluno não atingir a carga horaria minima de disciplinas eletivas do módulo, não está apto para conclusão
            if ((!is_null($modulo->mdo_cargahoraria_min_eletivas)) && ($cargaHorariaEletivas < $modulo->mdo_cargahoraria_min_eletivas)) {
                return false;
            }

            // se o aluno não atingir os creditos minimos de disciplinas eletivas do módulo, não está apto para conclusão
            if ((!is_null($modulo->mdo_creditos_min_eletivas)) && ($creditosEletivas < $modulo->mdo_creditos_min_eletivas)) {
                return false;
            }
        }

        return true;
    }

    private function verifyIfAlunoHaveCargaHorariaMinCurso(Matricula $matricula)
    {
        // busca a matriz curricular do curso
        $matrizCurricular = $matricula->turma->ofertacurso->matriz;

        // busca todas as disciplinas da matriz do curso
        $disciplinasMatriz = $this->matrizCurricularRepository
            ->getDisciplinasByMatrizId($matrizCurricular->mtc_id)
            ->pluck('mdc_id')
            ->toArray();

        $matriculasAluno = $this->matriculaOfertaDisciplinaRepository
            ->getMatriculasOfertasDisciplinasByMatricula($matricula->mat_id,
                ['ofd_trm_id' => $matricula->mat_trm_id]);

        $cargaHorariaAluno = 0;

        foreach ($matriculasAluno as $matriculaOferta) {
            if (in_array($matriculaOferta->mdc_id, $disciplinasMatriz)
                && in_array($matriculaOferta->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                $cargaHorariaAluno += $matriculaOferta->dis_carga_horaria;
            }
        }

        return $cargaHorariaAluno >= $matrizCurricular->mtc_horas;
    }

    private function verifyIfAlunoAprovadoTcc(Matricula $matricula)
    {
        // busca a matriz curricular do curso
        $matrizCurricular = $matricula->turma->ofertacurso->matriz;

        // busca a disciplina de tcc da matriz do curso
        $disciplinaTcc = $this->matrizCurricularRepository
            ->getDisciplinasByMatrizId($matrizCurricular->mtc_id, ['mdc_tipo_disciplina' => 'tcc'])
            ->first();

        if ($disciplinaTcc) {
            $matriculaOferta = $this->matriculaOfertaDisciplinaRepository->getMatriculasOfertasDisciplinasByMatricula($matricula->mat_id,
                ['ofd_mdc_id' => $disciplinaTcc->mdc_id, 'ofd_trm_id' => $matricula->mat_trm_id])[0];

            return in_array($matriculaOferta->mof_situacao_matricula, ['aprovado_media', 'aprovado_final']);
        }

        return true;
    }

    private function verifyIfAlunoHaveTccLancado(Matricula $matricula)
    {
        // busca a matriz curricular do curso
        $matrizCurricular = $matricula->turma->ofertacurso->matriz;

        // busca a disciplina de tcc da matriz do curso
        $disciplinaTcc = $this->matrizCurricularRepository
            ->getDisciplinasByMatrizId($matrizCurricular->mtc_id, ['mdc_tipo_disciplina' => 'tcc'])
            ->first();

        if ($disciplinaTcc) {
            $matriculaOferta = $this->matriculaOfertaDisciplinaRepository->getMatriculasOfertasDisciplinasByMatricula($matricula->mat_id,
                ['ofd_mdc_id' => $disciplinaTcc->mdc_id, 'ofd_trm_id' => $matricula->mat_trm_id])[0];

            if (in_array($matriculaOferta->mof_situacao_matricula, ['aprovado_media', 'aprovado_final']) && $matriculaOferta->tcc) {
                return true;
            }
        }

        return false;
    }

    private function verifyIfAlunoHaveTitulacaoGraduacao(Matricula $matricula)
    {
        $pessoa = $matricula->aluno->pessoa;

        $titulacaoGraduacao = $pessoa->titulacoes_informacoes()->where('tin_tit_id', '=', 1)->first();

        if ($titulacaoGraduacao) {
            return true;
        }

        return false;
    }

    public function getAlunosAptosCertificacao($turmaId, $moduloId, $poloId)
    {
        // busca todas as matriculas da turma
        if ($poloId) {
            $matriculas = $this->findAll(['mat_trm_id' => $turmaId, 'mat_pol_id' => $poloId], null, ['pes_nome' => 'asc']);
        } else {
            $matriculas = $this->findAll(['mat_trm_id' => $turmaId], null, ['pes_nome' => 'asc']);
        }

        $aptos = [];
        $certificados = [];

        if (!$matriculas->count()) {
            return array('aptos' => $aptos, 'certificados' => $certificados);
        }

        foreach ($matriculas as $matricula) {

            // Checar se aluno concluiu todas as disciplinas
            $turma = $this->turmaRepository->find($turmaId);
            $ofertaCurso = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

            // Se aluno concluiu todas as disciplinas, nao esta apto para certificacao
            if ($this->verifyIfAlunoIsAptoOrNot($matricula->mat_id)['status'] == 'success') {
                continue;
            }

            if ($this->registroRepository->matriculaTemRegistro($matricula->mat_id, $moduloId)) {
                $certificados[] = $matricula;
                continue;
            }

            // Verifica se o aluno esta apto para certificacao
            if ($this->verifyIfAlunoIsAptoCertificacao($matricula->mat_id, $turmaId, $moduloId)) {
                $aptos[] = $matricula;
            }
        }

        return array('aptos' => $aptos, 'certificados' => $certificados, 'aptosq' => COUNT($aptos), 'certificadosq' => COUNT($certificados));
    }

    private function verifyIfAlunoIsAptoCertificacao($matriculaId, $turmaId, $moduloId)
    {
        $apto = false;

        // busca as informacoes da oferta de curso
        $turma = $this->turmaRepository->find($turmaId);
        $ofertaCurso = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

        // busca as informacoes da matriz curricular do curso
        $matrizCurricular = $this->matrizCurricularRepository->find($ofertaCurso->ofc_mtc_id);

        // busca os modulos da matriz
        $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($matrizCurricular->mtc_id);

        // busca todas as disciplinas da matriz do curso
        $disciplinasMatriz = $this->matrizCurricularRepository
            ->getDisciplinasByMatrizId($matrizCurricular->mtc_id)
            ->pluck('mdc_id')
            ->toArray();

        // busca as informações da matricula
        $matricula = $this->find($matriculaId);

        if ($matricula->mat_situacao == 'concluido') {
            return false;
        }

        $quantDisciplinasObrigatorias = 0;
        $quantDisciplinasObrigatoriasAprovadas = 0;

        foreach ($modulos as $modulo) {
            if ($modulo->mdo_id > $moduloId) {
                break;
            }

            $disciplinasAluno = $this->matriculaOfertaDisciplinaRepository->getAllMatriculasByAlunoModuloMatriz($matricula->mat_alu_id, $modulo->mdo_id);

            if (!$disciplinasAluno->count()) {
                return false;
            }

            $cargaHorariaEletivas = 0;
            $creditosEletivas = 0;

            foreach ($disciplinasAluno as $disciplina) {
                if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                    $quantDisciplinasObrigatorias++;
                }
                // Verifica se a oferta de disciplina está na matriz do curso
                if (in_array($disciplina->mdc_id, $disciplinasMatriz)) {
                    // Caso o aluno foi aprovado na disciplina, incrementa a variavel
                    if (in_array($disciplina->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                        if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                            $quantDisciplinasObrigatoriasAprovadas++;
                        }

                        if ($disciplina->mdc_tipo_disciplina == 'eletiva') {
                            $cargaHorariaEletivas += $disciplina->dis_carga_horaria;
                            $creditosEletivas += $disciplina->dis_creditos;
                        }
                    }
                }
            }

            // se o aluno não atingir a carga horaria minima de disciplinas eletivas do módulo, não está apto para conclusão
            if ((!is_null($modulo->mdo_cargahoraria_min_eletivas)) && ($cargaHorariaEletivas < $modulo->mdo_cargahoraria_min_eletivas)) {
                return false;
            }

            // se o aluno não atingir os creditos minimos de disciplinas eletivas do módulo, não está apto para conclusão
            if ((!is_null($modulo->mdo_creditos_min_eletivas)) && ($creditosEletivas < $modulo->mdo_creditos_min_eletivas)) {
                return false;
            }
        }

        // Casos de situações
        if (($quantDisciplinasObrigatoriasAprovadas == $quantDisciplinasObrigatorias) && $quantDisciplinasObrigatorias != 0) {
            return true;
        }

        return false;
    }

    public function concluirMatricula($matriculaId)
    {
        // verifica se matricula existe
        $matricula = $this->model->find($matriculaId);

        if ($matricula) {
            // verifica se matricula está apta para conclusao
            $result = $this->verifyIfAlunoIsAptoOrNot($matriculaId);

            if ($result['status'] == 'success') {
                $data = [
                    'mat_situacao' => 'concluido',
                    'mat_data_conclusao' => date('d/m/Y')
                ];

                $matricula->fill($data)->save();

                return $matricula;
            }
        }

        return false;
    }

    public function paginateRequestByOfertaCurso(array $requestParameters = null)
    {
        $sort = array();
        if (empty($requestParameters)) {
            return new Collection();
        }

        if (empty($requestParameters['trm_id'])) {
            return new Collection();
        }

        $query = $this->model
            ->join('acd_turmas', 'mat_trm_id', '=', 'trm_id')
            ->join('acd_ofertas_cursos', 'trm_ofc_id', '=', 'ofc_id')
            ->join('acd_cursos', 'ofc_crs_id', '=', 'crs_id')
            ->leftJoin('acd_polos', 'mat_pol_id', '=', 'pol_id')
            ->leftJoin('acd_grupos', 'mat_grp_id', '=', 'grp_id')
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->select('mat_pol_id', 'mat_id', 'pes_nome', 'mat_situacao', 'trm_nome', 'pol_nome', 'pes_email')
            ->where('mat_trm_id', $requestParameters['trm_id']);

        if (array_key_exists('pol_id', $requestParameters)) {
            if ($requestParameters['pol_id']) {
                $query = $query->where('mat_pol_id', $requestParameters['pol_id']);
            }
        }

        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            $query = $query->orderBy($sort['field'], $sort['sort']);
        }

        if ($requestParameters['mat_situacao'] != null) {
            $query = $query->where('mat_situacao', $requestParameters['mat_situacao']);
        }

        return $query->paginate(15);
    }

    public function findAllBySitucao(array $requestParameters)
    {
        $query = $this->model
            ->join('acd_turmas', 'mat_trm_id', '=', 'trm_id')
            ->join('acd_ofertas_cursos', 'trm_ofc_id', '=', 'ofc_id')
            ->join('acd_cursos', 'ofc_crs_id', '=', 'crs_id')
            ->leftJoin('acd_polos', 'mat_pol_id', '=', 'pol_id')
            ->leftJoin('acd_grupos', 'mat_grp_id', '=', 'grp_id')
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->where('mat_trm_id', $requestParameters['trm_id'])
            ->orderBy('pes_nome', 'asc');

        if ($requestParameters['pol_id'] != null) {
            $query = $query->where('mat_pol_id', $requestParameters['pol_id']);
        }

        if ($requestParameters['mat_situacao'] != null) {
            $query = $query->where('mat_situacao', $requestParameters['mat_situacao']);
        }

        $matriculas = $query->get();

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

    public function getPrintData($IdMatricula, $IdModulo)
    {

        //recebe livro, folha e registro da certificação
        $livfolreg = DB::table('acd_registros')
            ->join('acd_certificados', 'reg_id', '=', 'crt_reg_id')
            ->where('crt_mat_id', '=', $IdMatricula)
            ->where('crt_mdo_id', '=', $IdModulo)
            ->join('acd_livros', 'reg_liv_id', '=', 'liv_id')
            ->first();

        if (!$livfolreg) {
            return null;
        }

        $matricula = $this->model->find($IdMatricula);
        $curso = $matricula->turma->ofertacurso->curso;
        $pessoa = $matricula->aluno->pessoa;

        $modulo = DB::table('acd_modulos_matrizes')
            ->where('mdo_id', $IdModulo)->first();

        $ofertasdisciplinas = DB::table('acd_ofertas_disciplinas')
            ->join('acd_matriculas_ofertas_disciplinas', 'mof_ofd_id', 'ofd_id')
            ->join('acd_modulos_disciplinas', 'ofd_mdc_id', 'mdc_id')
            ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
            ->where('mdc_mdo_id', $IdModulo)
            ->where('mof_mat_id', $IdMatricula)
            ->orderBy('dis_nome', 'asc')
            ->get();

        $cargahoraria = 0;
        $disciplinas = [];
        $numerador = 0;
        $denominador = 0;

        foreach ($ofertasdisciplinas as $modulodisciplina) {
            $disciplinas[] = $modulodisciplina->dis_nome;
            $cargahoraria = $cargahoraria + $modulodisciplina->dis_carga_horaria;
            $numerador = $modulodisciplina->mof_mediafinal + $numerador;
            $denominador++;
        }

        //formata o coeficiente do módulo
        $coeficiente = $numerador / $denominador;
        $coeficiente = number_format($coeficiente, 2, '.', '');

        $descricaomodulo = $modulo->mdo_descricao;
        $qualificacaomodulo = $modulo->mdo_qualificacao;

        $query = DB::table('gra_documentos')
            ->where('doc_pes_id', $pessoa->pes_id)
            ->where('doc_tpd_id', 2)
            ->first();
        $nomepessoa = $pessoa->pes_nome;

        //formatação do CPF de pessoa
        $cpfpessoa = $query->doc_conteudo;
        $parte_um = substr($cpfpessoa, 0, 3);
        $parte_dois = substr($cpfpessoa, 3, 3);
        $parte_tres = substr($cpfpessoa, 6, 3);
        $parte_quatro = substr($cpfpessoa, 9, 2);
        $cpfpessoaformatado = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";

        //prepara o array de retorno
        $returnData = [
            'DESCRICAOMODULO' => mb_strtoupper($descricaomodulo, "UTF-8"),
            'QUALIFICACAOMODULO' => mb_strtoupper($qualificacaomodulo, "UTF-8"),
            'CARGAHORARIAMODULO' => $cargahoraria,
            'DISCIPLINAS' => $disciplinas,
            'EIXOCURSO' => mb_strtoupper($curso->crs_eixo, "UTF-8"),
            'LIVRO' => str_pad((string) $livfolreg->liv_numero, 4, '0', STR_PAD_LEFT),
            'FOLHA' => str_pad((string) $livfolreg->reg_folha, 4, '0', STR_PAD_LEFT),
            'REGISTRO' => str_pad((string) $livfolreg->reg_registro, 4, '0', STR_PAD_LEFT),
            'COEFICIENTEDOMODULO' => $coeficiente,
            'PESSOANOME' => mb_strtoupper($nomepessoa, "UTF-8"),
            'PESSOACPF' => $cpfpessoaformatado
        ];
        return $returnData;
    }

    public function getMatriculasPorStatus()
    {
        $result = DB::table('acd_matriculas')
            ->select('mat_situacao', DB::raw("COUNT(*) as quantidade"))
            ->groupBy('mat_situacao')->get()->toArray();

        foreach ($result as $key => $item) {
            $result[$key]->mat_situacao = ucfirst($result[$key]->mat_situacao);
        }

        return $result;
    }

    public function getMatriculasPorMesUltimosSeisMeses(): SupportCollection
    {
        $fimPeriodo = new \DateTime('first day of next month');
        $inicioPeriodo = $fimPeriodo->sub(new \DateInterval('P6M'));

        // Traz todas as matriculas do periodo
        $historico = DB::table('acd_matriculas')->where('created_at', '>=', $inicioPeriodo->format('Y-m-d') . ' 00:00:00')->get();

        $result = collect([]);

        for ($i = 0; $i < 6; $i++) {
            $data = [];

            $matriculasMes = $historico->filter(function ($value, $key) use ($inicioPeriodo) {
                $createdAt = new \DateTime($value->created_at);
                return (int)$createdAt->format('m') == (int)$inicioPeriodo->format('m');
            });

            $data['mes'] = $this->meses[(int)$inicioPeriodo->format('m')] . '/' . $inicioPeriodo->format('y');
            $data['quantidade'] = $matriculasMes->count();

            $result[] = $data;

            $inicioPeriodo = $inicioPeriodo->add(new \DateInterval('P1M'));
        }

        return $result;
    }
}
