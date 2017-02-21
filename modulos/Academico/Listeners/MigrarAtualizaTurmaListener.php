<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Models\Turma;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Academico\Events\AtualizarTurmaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Moodle;

class MigrarAtualizaTurmaListener
{
    private $turmaRepository;
    private $cursoRepository;
    private $periodoLetivoRepository;
    private $ambienteVirtualRepository;
    private $sincronizacaoRepository;

    public function __construct(TurmaRepository $turmaRepository,
                                CursoRepository $cursoRepository,
                                PeriodoLetivoRepository $periodoLetivoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                SincronizacaoRepository $sincronizacaoRepository)
    {
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(AtualizarTurmaEvent $event)
    {
        //$ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($event->trm_id);

        $turma = $event->getData();

        $data['course']['trm_id'] = $turma->trm_id;
        $data['course']['shortname'] = $this->turmaShortName($turma);
        $data['course']['fullname'] = $this->turmaFullName($turma);

        $param['url'] = $ambiente->url;
        $param['token'] = $ambiente->token;
        $param['action'] = 'post';
        $param['functioname'] = 'local_integracao_update_course';
        $param['data'] = $data;

        $response = Moodle::send($param);
        $status = 3;

        if (array_key_exists('status', $response) && $response['status'] == 'success') {
            $status = 2;
        }

        event(new AtualizarSyncEvent($turma, $status, $response['message']));

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
