<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Events\CreateGrupoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class CreateGrupoListener
{
    protected $ambienteVirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(CreateGrupoEvent $event)
    {
        try {
            $grupo = $event->getData();

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

            if (!$ambiente) {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->ambienteservico->last();

            if ($ambServico) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['functioname'] = $event->getEndpoint();
                $param['action'] = 'CREATE';

                $param['data']['group']['trm_id'] = $grupo->grp_trm_id;
                $param['data']['group']['grp_id'] = $grupo->grp_id;
                $param['data']['group']['name'] = $grupo->grp_nome;
                $param['data']['group']['description'] = '';

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent($grupo, $status, $response['message']));
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
