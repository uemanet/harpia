<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Events\UpdateTurmaEvent;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class UpdateTurmaListener
{
    private $turmaRepository;
    private $ambienteVirtualRepository;

    public function __construct(
        TurmaRepository $turmaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->turmaRepository = $turmaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(UpdateTurmaEvent $event)
    {
        try {
            $turma = $event->getData();

            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

            if ($ambiente) {
                $data['course']['trm_id'] = $turma->trm_id;
                $data['course']['shortname'] = $this->turmaRepository->shortName($turma);
                $data['course']['fullname'] = $this->turmaRepository->fullName($turma);

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'UPDATE';
                $param['functioname'] = $event->getEndpoint();
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($turma, $status, $response['message'], $param['action']));
            }
        } catch (ConnectException $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, $exception->getMessage(), $event->getAction()));
        } catch (ClientException $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, $exception->getMessage(), $event->getAction()));
        } catch (\Exception $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, $exception->getMessage(), $event->getAction()));
        } finally {
            return true;
        }
    }
}
