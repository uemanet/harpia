<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Events\DeleteGrupoEvent;
use Modulos\Integracao\Events\DeleteSincronizacaoEvent;
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
        $grupo = $event->getData();

        try {
            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($event->getExtra());

            if ($ambiente) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['functioname'] = $event->getEndpoint();
                $param['action'] = 'DELETE';

                $param['data']['group']['grp_id'] = $grupo->grp_id;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new DeleteSincronizacaoEvent(
                    $grupo->getTable(),
                    $grupo->grp_id,
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
                $grupo->getTable(),
                $grupo->grp_id,
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
                $grupo->getTable(),
                $grupo->grp_id,
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
