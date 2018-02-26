<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Events\DeleteOfertaDisciplinaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class DeleteOfertaDisciplinaListener
{
    protected $ambienteVirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeleteOfertaDisciplinaEvent $event)
    {
        $oferta = $event->getData();

        try {
            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->find($event->getExtra());

            if (!$ambiente) {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->integracao();

            if ($ambServico) {
                $data['discipline']['ofd_id'] = $oferta->ofd_id;

                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['action'] = 'post';
                $param['functionname'] = $event->getEndpoint();
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent(
                    $oferta,
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

            event(new UpdateSincronizacaoEvent($event->getData(), 3, get_class($exception), $event->getAction()));
        } finally {
            return true;
        }
    }
}
