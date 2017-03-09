<?php

namespace Modulos\Integracao\Listeners;

use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Integracao\Events\DeleteOfertaTurmaEvent;
use Moodle;

class MigrarExclusaoOfertaTurmaListener
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

    public function handle(DeleteOfertaTurmaEvent $event)
    {
        $turmasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_turmas',
            'sym_status' => 1,
            'sym_action' => 'DELETE'
        ]);

        if ($turmasMigrar->count()) {
            foreach ($turmasMigrar as $item) {
                $turma = $this->turmaRepository->find($item->sym_table_id);
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

                if (!$ambiente) {
                    continue;
                }

                $data['course']['trm_id'] = $turma->trm_id;

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'post';
                $param['functioname'] = 'local_integracao_delete_course';
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new AtualizarSyncEvent($turma, $status, $response['message'], $event->getAction()));
            }
        }
    }
}
