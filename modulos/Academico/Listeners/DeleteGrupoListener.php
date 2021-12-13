<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Events\DeleteGrupoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class DeleteGrupoListener
{
    protected $ambienteVirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeleteGrupoEvent $event)
    {
        try {
            $grupo = $event->getData();

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->find($event->getExtra());

            if (!$ambiente) {
                return;
            }

            if ($event->getVersion() != 'v1') {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->integracao();

            if ($ambServico) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['functionname'] = $event->getEndpoint();
                $param['action'] = 'DELETE';

                $param['data']['group']['grp_id'] = $grupo->grp_id;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent(
                    $grupo,
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
