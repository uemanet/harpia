<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;

class HistoricoDefinitivoRepository extends BaseRepository
{
    private $matriculaOfertaDisciplinaRepository;
    private $lancamentoTccRepository;

    public function __construct(
        Matricula $model,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        LancamentoTccRepository $lancamentoTccRepository
    ) {
        $this->model = $model;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->lancamentoTccRepository = $lancamentoTccRepository;
    }

    public function getGradeCurricularByMatricula($matriculaId)
    {
        $matricula = $this->model->find($matriculaId);

        $returndata = array();

        $curso = $matricula->turma->ofertacurso->curso;

        $returndata['curso'] = $curso;
        $returndata['pessoa'] = $matricula->aluno->pessoa;

        $returndata['disciplinas'] = $this->matriculaOfertaDisciplinaRepository->findBy(
            ['mof_mat_id' => $matricula->mat_id],
            null,
            ['mdo_id' => 'asc', 'dis_nome' => 'asc']
        );

        $returndata['tcc'] = $this->lancamentoTccRepository->findBy(
            ['ltc_id' => $matricula->mat_ltc_id],
            ['ltc_titulo', 'ltc_tipo', 'ltc_data_apresentacao', 'ltc_observacao', 'pes_nome']
        )->first();

        return $returndata;
    }
}
