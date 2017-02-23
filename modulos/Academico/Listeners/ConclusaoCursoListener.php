<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\ConclusaoCursoEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Moodle;

class ConclusaoCursoListener
{
    private $sincronizacaoRepository;
    private $matriculaCursoRepository;
    private $ambienteVirtualRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    )
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handler(ConclusaoCursoEvent $event)
    {
        $matriculasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_matriculas',
            'sym_status' => 1,
            'sym_action' => $event->getAction()
        ]);

        if ($matriculasMigrar->count()) {
            foreach ($matriculasMigrar as $reg) {
                $matricula = $this->matriculaCursoRepository->find($reg->sym_table_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matricula->mat_trm_id);

                if ($ambiente) {
                    $param = [];

                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = '';
                    $param['action'] = $event->getAction();

                    $retorno = Moodle::send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno) && $retorno['status'] == 'success') {
                        $status = 2;
                    }

                    event(new AtualizarSyncEvent($matricula, $status, $retorno['message']));
                }
            }
        }
    }
}