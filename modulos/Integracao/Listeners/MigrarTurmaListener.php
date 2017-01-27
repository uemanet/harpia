<?php

namespace Modulos\Integracao\Listeners;

use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\TurmaMapeadaEvent;

class MigrarTurmaListener
{
    private $turmaRepository;
    private $cursoRepository;
    private $periodoLetivoRepository;

    public function __construct(TurmaRepository $turmaRepository,
                                CursoRepository $cursoRepository,
                                PeriodoLetivoRepository $periodoLetivoRepository)
    {
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
    }

    public function handle(TurmaMapeadaEvent $event)
    {
        $turma = $event->getData();

        $data['course']['trm_id'] = $turma->trm_id;
        $data['course']['category'] = 1;
        $data['course']['shortname'] = $this->turmaShortName($turma); // CC_TURMA_A_2017.1
        $data['course']['fullname'] = $this->turmaFullName($turma); // Ciência da Computação - Turma A - 2017.1
        $data['course']['summaryformats'] = 1;
        $data['course']['format'] = 'topics';
        $data['course']['numsections'] = 0;
    }

    /**
     * @param Turma $turma
     * @return mixed|string
     */
    private function turmaShortName(Turma $turma)
    {
        $cursoId = $this->turmaRepository->getCurso($turma->trm_id);
        $curso = $this->cursoRepository->find($cursoId);
        $periodoLetivo = $this->periodoLetivoRepository->find($turma->trm_per_id);

        $shortName = $curso->crs_sigla .' ' . $turma->trm_nome . ' ' . $periodoLetivo->per_nome;
        $shortName = str_replace(' ', '_', $shortName);

        return $shortName;
    }

    /**
     * @param Turma $turma
     * @return string
     */
    private function turmaFullName(Turma $turma)
    {
        $cursoId = $this->turmaRepository->getCurso($turma->trm_id);
        $curso = $this->cursoRepository->find($cursoId);
        $periodoLetivo = $this->periodoLetivoRepository->find($turma->trm_per_id);

        $fullname = $curso->crs_nome .' - ' . $turma->trm_nome . ' - ' . $periodoLetivo->per_nome;

        return $fullname;
    }
}
