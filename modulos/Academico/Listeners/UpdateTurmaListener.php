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

            if (!$ambiente) {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->ambienteservico->last();

            if ($ambServico) {
                $data['course']['trm_id'] = $turma->trm_id;
                $data['course']['shortname'] = $this->turmaRepository->shortName($turma);
                $data['course']['fullname'] = $this->turmaRepository->fullName($turma);

                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['action'] = 'UPDATE';
                $param['functionname'] = $event->getEndpoint();
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($turma, $status, $response['message'], $param['action']));
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
