<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Repositories\TitulacaoInformacaoRepository;

class HistoricoDefinitivoRepository extends BaseRepository
{
    private $matriculaOfertaDisciplinaRepository;
    private $lancamentoTccRepository;
    private $titulacaoInformacaoRepository;

    public function __construct(
        Matricula $model,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        LancamentoTccRepository $lancamentoTccRepository,
        TitulacaoInformacaoRepository $titulacaoInformacaoRepository
    ) {
        $this->model = $model;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->lancamentoTccRepository = $lancamentoTccRepository;
        $this->titulacaoInformacaoRepository = $titulacaoInformacaoRepository;
    }

    public function getGradeCurricularByMatricula($matriculaId)
    {
        $matricula = $this->model->find($matriculaId);

        $returndata = array();

        $curso = $matricula->turma->ofertacurso->curso;

        $turma = $matricula->turma;
        $turma->periodo_letivo = $turma->periodo->per_nome;

        $returndata['curso'] = $curso;
        $returndata['turma'] = $turma;
        $returndata['polo'] = $matricula->polo;

        $pessoa['nome'] = $matricula->aluno->pessoa->pes_nome;
        $pessoa['sexo'] = ($matricula->aluno->pessoa->pes_sexo == 'M') ? 'MASCULINO' : 'FEMININO';
        $pessoa['mae'] = $matricula->aluno->pessoa->pes_mae;
        $pessoa['pai'] = $matricula->aluno->pessoa->pes_pai;

        $rg = $matricula->aluno->pessoa->documentos()->where('doc_tpd_id', 1)->first();
        $cpf = $matricula->aluno->pessoa->documentos()->where('doc_tpd_id', 2)->first();

        $pessoa['rg'] = [
            'conteudo' => $rg->doc_conteudo,
            'orgao' => $rg->doc_orgao,
            'data_expedicao' => $rg->doc_data_expedicao
        ];

        $pessoa['cpf'] = $cpf->doc_conteudo;

        $pessoa['nascimento'] = $matricula->aluno->pessoa->pes_nascimento;
        $pessoa['naturalidade'] = $matricula->aluno->pessoa->pes_naturalidade;
        $pessoa['nacionalidade'] = $matricula->aluno->pessoa->pes_nacionalidade;
        $pessoa['matricula'] = $matricula->mat_id;

        $returndata['pessoa'] = $pessoa;

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $returndata['data'] = 'São Luís, '.strftime('%d de %B de %Y', strtotime('today'));

        if ($curso->crs_nvc_id == 1) {
            $returndata['modulos'] = $this->getDisciplinasTecnico($matricula->mat_id);

            return $returndata;
        }

        $returndata['disciplinas'] = $this->getDisciplinasGraduacao($matricula->mat_id);

        $returndata['tcc'] = $this->lancamentoTccRepository->findBy(
            ['ltc_id' => $matricula->mat_ltc_id],
            ['ltc_titulo', 'ltc_tipo', 'ltc_data_apresentacao', 'ltc_observacao']
        )->first();

        return $returndata;
    }

    private function getDisciplinasGraduacao($matriculaId)
    {
        $matricula = $this->model->find($matriculaId);

        $modulos = $matricula->turma->ofertacurso->matriz->modulos;

        $arrDisciplinas = [];

        foreach ($modulos as $modulo) {
            $disciplinasModulo = $modulo->disciplinas()->orderBy('dis_nome', 'asc')->get();

            foreach ($disciplinasModulo as $disciplina) {
                $result = $this->matriculaOfertaDisciplinaRepository->findBy(
                    [
                        ['mof_mat_id', '=', $matriculaId],
                        ['dis_id', '=', $disciplina->dis_id],
                        ['mof_situacao_matricula', '<>', ['cursando', 'cancelado']]
                    ],
                    ['mof_id', 'mof_nota1', 'mof_nota2', 'mof_nota3', 'mof_conceito', 'mof_recuperacao', 'mof_final',
                        'mof_mediafinal', 'mof_situacao_matricula', 'mdo_id', 'mdo_nome', 'mdo_descricao', 'mdo_qualificacao',
                        'dis_nome', 'dis_carga_horaria', 'dis_creditos', 'pes_id', 'pes_nome as professor']
                )->last();

                if ($result) {
                    $arrDisciplinas[] = $result;
                }
            }
        }

        for ($i = 0; $i < count($arrDisciplinas); $i++) {
            $titulacao = null;
            $arrDisciplinas[$i]->professor_titulacao = '';

            $result = $this->titulacaoInformacaoRepository->findBy(
                ['tin_pes_id' => $arrDisciplinas[$i]->pes_id],
                null,
                ['tit_peso' => 'desc']
            );

            if ($result) {
                $titulacao = $result->where('tin_anofim', '<>', null)->first();

                if ($titulacao->tit_id == 2) {
                    $arrDisciplinas[$i]->professor_titulacao = 'Graduado';
                } elseif ($titulacao->tit_id == 3) {
                    $arrDisciplinas[$i]->professor_titulacao = 'Especialista';
                } elseif ($titulacao->tit_id == 4) {
                    $arrDisciplinas[$i]->professor_titulacao = 'Mestre';
                } elseif ($titulacao->tit_id == 5) {
                    $arrDisciplinas[$i]->professor_titulacao = 'Doutor';
                } elseif ($titulacao->tit_id == 6) {
                    $arrDisciplinas[$i]->professor_titulacao = 'Pós-Doutor';
                } elseif ($titulacao->tit_id == 7) {
                    $arrDisciplinas[$i]->professor_titulacao = 'Pós-Graduado';
                }
            }
        }

        return $arrDisciplinas;
    }

    private function getDisciplinasTecnico($matriculaId)
    {
        $matricula = $this->model->find($matriculaId);

        $modulos = $matricula->turma->ofertacurso->matriz->modulos;

        $return = array();

        foreach ($modulos as $modulo) {
            $arrModulo = [];

            $arrModulo['id'] = $modulo->mdo_id;
            $arrModulo['nome'] = $modulo->mdo_nome;
            $arrModulo['descricao'] = $modulo->mdo_descricao;
            $arrModulo['qualificacao'] = $modulo->mdo_qualificacao;

            $disciplinasModulo = $modulo->disciplinas()->orderBy('dis_nome', 'asc')->get();

            $arrDisciplinas = [];

            foreach ($disciplinasModulo as $disciplina) {
                $result = $this->matriculaOfertaDisciplinaRepository->findBy(
                    [
                        ['mof_mat_id', '=', $matriculaId],
                        ['dis_id', '=', $disciplina->dis_id],
                        ['mof_situacao_matricula', '<>', ['cursando', 'cancelado']]
                    ],
                    ['mof_id', 'mof_nota1', 'mof_nota2', 'mof_nota3', 'mof_conceito', 'mof_recuperacao', 'mof_final',
                        'mof_mediafinal', 'mof_situacao_matricula', 'mdo_id', 'mdo_nome', 'mdo_descricao', 'mdo_qualificacao',
                        'dis_nome', 'dis_carga_horaria', 'dis_creditos', 'pes_id', 'pes_nome as professor']
                )->last();

                if ($result) {
                    $arrDisciplinas[] = $result;
                }
            }

            for ($i = 0; $i < count($arrDisciplinas); $i++) {
                $titulacao = null;
                $arrDisciplinas[$i]->professor_titulacao = '';

                $result = $this->titulacaoInformacaoRepository->findBy(
                    ['tin_pes_id' => $arrDisciplinas[$i]->pes_id],
                    null,
                    ['tit_peso' => 'desc']
                );

                if ($result) {
                    $titulacao = $result->where('tin_anofim', '<>', null)->first();

                    if ($titulacao->tit_id == 2) {
                        $arrDisciplinas[$i]->professor_titulacao = 'Graduado';
                    } elseif ($titulacao->tit_id == 3) {
                        $arrDisciplinas[$i]->professor_titulacao = 'Especialista';
                    } elseif ($titulacao->tit_id == 4) {
                        $arrDisciplinas[$i]->professor_titulacao = 'Mestre';
                    } elseif ($titulacao->tit_id == 5) {
                        $arrDisciplinas[$i]->professor_titulacao = 'Doutor';
                    } elseif ($titulacao->tit_id == 6) {
                        $arrDisciplinas[$i]->professor_titulacao = 'Pós-Doutor';
                    } elseif ($titulacao->tit_id == 7) {
                        $arrDisciplinas[$i]->professor_titulacao = 'Pós-Graduado';
                    }
                }
            }

            $arrModulo['disciplinas'] = $arrDisciplinas;

            $return[] = $arrModulo;
        }

        return $return;
    }
}
