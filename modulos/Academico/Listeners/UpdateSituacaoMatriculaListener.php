<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Events\UpdateSituacaoMatriculaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class UpdateSituacaoMatriculaListener
{
    protected $ambienteVirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(UpdateSituacaoMatriculaEvent $event)
    {
        try {
            $matriculaTurma = $event->getData();

            // ambiente virtual vinculado à turma
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaTurma->mat_trm_id);

            if ($ambiente) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['functioname'] = $event->getEndpoint();
                $param['action'] = 'UPDATE_SITUACAO_MATRICULA';

                $param['data']['student']['trm_id'] = $matriculaTurma->mat_trm_id;
                $param['data']['student']['pes_id'] = $matriculaTurma->aluno->alu_pes_id;
                $param['data']['student']['new_status'] = $matriculaTurma->mat_situacao;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent(
                    $matriculaTurma,
                    $status,
                    $response['message'],
                    'UPDATE_SITUACAO_MATRICULA'
                ));
            }
        } catch (ConnectException | ClientException | \Exception $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, get_class($exception), $event->getAction()));
        } finally {
            return true;
        }
    }
}
