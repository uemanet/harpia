<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\MapeamentoNota;

class MapeamentoNotaRepository extends BaseRepository
{
    protected $periodoLetivoRepository;
    protected $ofertaDisciplinaRepository;

    public function __construct(
        MapeamentoNota $model,
        PeriodoLetivoRepository $periodoLetivoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository
    ) {
        $this->model = $model;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
    }

    public function getGradeCurricularByTurma($turmaId)
    {
        $periodos = $this->periodoLetivoRepository->getAllByTurma($turmaId);

        $returndata = array();

        foreach ($periodos as $periodo) {
            $reg = array();

            $reg['per_id'] = $periodo->per_id;
            $reg['per_nome'] = $periodo->per_nome;

            $ofertas = $this->ofertaDisciplinaRepository->findAllWithMapeamentoNotas([
                'ofd_trm_id' => $turmaId,
                'ofd_per_id' => $periodo->per_id
            ], null, ['dis_nome' => 'asc']);

            if ($ofertas->count()) {
                $reg['ofertas'] = $ofertas;

                $returndata[] = $reg;
            }
        }

        return $returndata;
    }
}
