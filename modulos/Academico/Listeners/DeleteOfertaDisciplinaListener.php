<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\DeleteSincronizacaoEvent;
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
            $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($event->getExtra());

            if ($ambiente) {
                $data['discipline']['ofd_id'] = $oferta->ofd_id;

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'post';
                $param['functioname'] = $event->getEndpoint();
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new DeleteSincronizacaoEvent(
                    $oferta->getTable(),
                    $oferta->ofd_id,
                    $status,
                    $response['message'],
                    'DELETE',
                    null,
                    $event->getExtra()
                ));
            }
        } catch (ConnectException $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            event(new DeleteSincronizacaoEvent(
                $oferta->getTable(),
                $oferta->ofd_id,
                3,
                $exception->getMessage(),
                $event->getAction(),
                null,
                $event->getExtra()
            ));
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            event(new DeleteSincronizacaoEvent(
                $oferta->getTable(),
                $oferta->ofd_id,
                3,
                $exception->getMessage(),
                $event->getAction(),
                null,
                $event->getExtra()
            ));
        } finally {
            return true;
        }
    }
}
