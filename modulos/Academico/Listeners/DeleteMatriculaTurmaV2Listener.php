<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Events\DeleteMatriculaTurmaEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class DeleteMatriculaTurmaV2Listener
{
    protected $ambienteVirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeleteMatriculaTurmaEvent $event)
    {
        try {
            $matricula = $event->getData();

            // ambiente virtual vinculado à turma da matricula em disciplina
            $ambiente = $this->ambienteVirtualRepository->find($event->getExtra());

            if (!$ambiente) {
                return;
            }

            if ($event->getVersion() != 'v2') {
                return;
            }
            // Web service de integracao
            $ambServico = $ambiente->integracaoV2();

            if ($ambServico) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['functionname'] = $event->getEndpointV2();
                $param['action'] = 'DELETE';

                $param['data']['student']['mat_id'] = $matricula->mat_id;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent(
                    $matricula,
                    $status,
                    $response['message'],
                    $event->getAction(),
                    null,
                    $event->getExtra()
                ));
            }
        } catch (ConnectException | ClientException | \Exception $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, get_class($exception), $event->getAction(), null, $event->getExtra()));
        } finally {
            return true;
        }
    }
}
