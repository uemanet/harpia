<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Models\Turma;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
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
        $turmasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_turmas',
            'sym_status' => 1,
            'sym_action' => 'UPDATE'
        ]);

        if ($turmasMigrar->count()) {
            foreach ($turmasMigrar as $item) {
                $turma = $this->turmaRepository->find($item->sym_table_id);
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

                if (!$ambiente) {
                    continue;
                }

                if ($ambiente) {
                    $data['course']['trm_id'] = $turma->trm_id;
                    $data['course']['shortname'] = $this->turmaRepository->shortName($turma);
                    $data['course']['fullname'] = $this->turmaRepository->fullName($turma);

                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['action'] = 'UPDATE';
                    $param['functioname'] = 'local_integracao_update_course';
                    $param['data'] = $data;

                    $response = Moodle::send($param);
                    $status = 3;

                    if (array_key_exists('status', $response) && $response['status'] == 'success') {
                        $status = 2;
                    }

                    event(new UpdateSincronizacaoEvent($turma, $status, $response['message'], $param['action']));
                }
            }
        }
    }
}
