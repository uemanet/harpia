<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;

class HistoricoParcialRepository
{
    private $periodoLetivoRepository;
    private $matriculaOfertaDisciplinaRepository;

    public function __construct(
        Matricula $model,
        PeriodoLetivoRepository $periodoLetivoRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository
    ) {
        $this->model = $model;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
    }

    public function getGradeCurricularByMatricula($matriculaId)
    {
        $matricula = $this->model->find($matriculaId);

        $periodos = $this->periodoLetivoRepository->getAllByTurma($matricula->mat_trm_id);

        $returndata = array();

        foreach ($periodos as $periodo) {
            $reg = array();

            $reg['per_id'] = $periodo->per_id;
            $reg['per_nome'] = $periodo->per_nome;

            $disciplinasCursadas = $this->matriculaOfertaDisciplinaRepository->findBy([
                ['mof_mat_id', '=', $matricula->mat_id],
                ['ofd_per_id', '=', $periodo->per_id]
            ], null, ['dis_nome' => 'asc', 'mdo_id' => 'asc']);

            if (!$disciplinasCursadas->count()) {
                continue;
            }

            $disciplinasPeriodo = array();

            // pegar as matriculas do aluno para as disciplinas desse modulo

            foreach ($disciplinasCursadas as $oferta) {
                $cell = array();

                $cell['mof_id'] = $oferta->mof_id;
                $cell['dis_nome'] = $oferta->dis_nome;
                $cell['ofd_tipo_avaliacao'] = $oferta->ofd_tipo_avaliacao;
                $cell['mdc_tipo_disciplina'] = $oferta->mdc_tipo_disciplina;
                $cell['mdo_nome'] = $oferta->mdo_nome;
                $cell['mof_nota1'] = '---';
                $cell['mof_nota2'] = '---';
                $cell['mof_nota3'] = '---';
                $cell['mof_conceito'] = '---';
                $cell['mof_recuperacao'] = '---';
                $cell['mof_final'] = '---';
                $cell['mof_mediafinal'] = '---';
                $cell['mof_situacao_matricula'] = '---';

                if (!is_null($oferta->mof_nota1)) {
                    $cell['mof_nota1'] = number_format((float)$oferta->mof_nota1, 1);
                }

                if (!is_null($oferta->mof_nota2)) {
                    $cell['mof_nota2'] = number_format((float)$oferta->mof_nota2, 1);
                }

                if (!is_null($oferta->mof_nota3)) {
                    $cell['mof_nota3'] = number_format((float)$oferta->mof_nota3, 1);
                }

                if (!is_null($oferta->mof_conceito)) {
                    $cell['mof_conceito'] = $oferta->mof_conceito;
                }

                if (!is_null($oferta->mof_recuperacao)) {
                    $cell['mof_recuperacao'] = number_format((float)$oferta->mof_recuperacao, 1);
                }

                if (!is_null($oferta->mof_final)) {
                    $cell['mof_final'] = number_format((float)$oferta->mof_final, 1);
                }

                if (!is_null($oferta->mof_mediafinal)) {
                    $cell['mof_mediafinal'] = number_format((float)$oferta->mof_mediafinal, 1);
                }

                $cell['mof_situacao_matricula'] = $oferta->mof_situacao_matricula;
                $cell['dis_carga_horaria'] = $oferta->dis_carga_horaria;

                $disciplinasPeriodo[] = (object)$cell;
            }

            $reg['ofertas_disciplinas'] = $disciplinasPeriodo;

            $returndata[] = $reg;
        }

        return $returndata;
    }
}
