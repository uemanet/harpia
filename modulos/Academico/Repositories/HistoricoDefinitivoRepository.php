<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Geral\Repositories\TitulacaoInformacaoRepository;
use DB;

class HistoricoDefinitivoRepository
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

        // buscar a graduacao da pessoa
        $pessoa['graduacao'] = $matricula->aluno->pessoa->titulacoes_informacoes()->where('tin_tit_id', '=', 1)->first();

        $returndata['pessoa'] = $pessoa;

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $returndata['data'] = 'São Luís, ' . strftime('%d de %B de %Y', strtotime('today'));

        if ($curso->crs_nvc_id == 2) {
            $returndata['modulos'] = $this->getDisciplinasTecnico($matricula->mat_id);

            return $returndata;
        }

        $returndata['disciplinas'] = $this->getDisciplinasGraduacao($matricula->mat_id);

        $mof_tcc = DB::table('acd_matriculas_ofertas_disciplinas')
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->where('mof_mat_id', '=', $matricula->mat_id)
            ->select('mof_id')
            ->first();

        $tcc = $this->lancamentoTccRepository->findBy(
            ['ltc_mof_id' => $mof_tcc->mof_id],
            ['ltc_titulo', 'ltc_tipo', 'ltc_data_apresentacao', 'ltc_observacao', 'pes_nome', 'pes_id']
        )->first();

        if ($tcc) {
            $tcc->prf_titulacao = $this->getTitulacaoMaxProfessor($tcc->pes_id);
        }

        $returndata['tcc'] = $tcc;

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
                        ['mof_situacao_matricula', '<>', ['cursando', 'cancelado', 'reprovado_media', 'reprovado_final']]
                    ],
                    ['mof_id', 'mof_nota1', 'mof_nota2', 'mof_nota3', 'mof_conceito', 'mof_recuperacao', 'mof_final',
                        'mof_mediafinal', 'mof_situacao_matricula', 'mdc_tipo_disciplina', 'mdo_id', 'mdo_nome', 'mdo_descricao', 'mdo_qualificacao',
                        'dis_nome', 'dis_carga_horaria', 'dis_creditos', 'pes_id', 'pes_nome as professor']
                )->last();

                if ($result) {
                    $arrDisciplinas[] = $result;
                }
            }
        }

        for ($i = 0; $i < count($arrDisciplinas); $i++) {
            $arrDisciplinas[$i]->professor_titulacao = $this->getTitulacaoMaxProfessor($arrDisciplinas[$i]->pes_id);
        }

        return $arrDisciplinas;
    }

    private function getTitulacaoMaxProfessor($pes_id)
    {
        $result = $this->titulacaoInformacaoRepository->findBy(
            ['tin_pes_id' => $pes_id],
            null,
            ['tit_peso' => 'desc']
        );

        /*
         * Titulacoes ID
         * Graduação -> 1
         * Mestrado -> 2
         * Doutorado -> 3
         * Pós-Doutorado -> 4
         * Especialização -> 5
         * Ensino Médio -> 6
         */

        $titulacoes = [
            1 => 'Graduado',
            2 => 'Mestre',
            3 => 'Doutor',
            4 => 'Pós-Doutor',
            5 => 'Especialista'
        ];

        if ($result) {
            $titulacao = $result->where('tin_anofim', '<>', null)->first();

            if ($titulacao && array_key_exists($titulacao->tit_id, $titulacoes)) {
                return $titulacoes[$titulacao->tit_id];
            }
        }

        return null;
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
            $arrModulo['competencias'] = $modulo->mdo_competencias;

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
                        'dis_nome', 'dis_carga_horaria', 'dis_creditos', 'pes_id', 'pes_nome as professor', 'ofd_tipo_avaliacao']
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

                $titulacoes = [
                    2 => 'Graduado',
                    3 => 'Especialista',
                    4 => 'Mestre',
                    5 => 'Doutor',
                    6 => 'Pós-Doutor',
                    7 => 'Pós-Graduado'
                ];

                if ($result) {
                    $titulacao = $result->where('tin_anofim', '<>', null)->first();

                    if ($titulacao && array_key_exists($titulacao->tit_id, $titulacoes)) {
                        $arrDisciplinas[$i]->professor_titulacao = $titulacoes[$titulacao->tit_id];
                    }
                }
            }

            $arrModulo['disciplinas'] = $arrDisciplinas;

            $return[] = $arrModulo;
        }

        return $return;
    }
}
