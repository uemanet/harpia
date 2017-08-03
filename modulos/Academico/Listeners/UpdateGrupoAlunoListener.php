<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Events\UpdateGrupoAlunoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class UpdateGrupoAlunoListener
{
    protected $ambienteVirtualRepository;

    public function __construct(AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(UpdateGrupoAlunoEvent $event)
    {
        try {
            $matricula = $event->getData();

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matricula->mat_trm_id);

            if ($ambiente) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['functioname'] = $event->getEndpoint();
                $param['action'] = 'UPDATE_GRUPO_ALUNO';

                $param['data']['student']['mat_id'] = $matricula->mat_id;
                $param['data']['student']['pes_id'] = $matricula->aluno->alu_pes_id;
                if ($event->getExtra()) {
                    $param['data']['student']['old_grp_id'] = $event->getExtra();
                }
                $param['data']['student']['new_grp_id'] = $matricula->mat_grp_id;

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
                    $param['action'],
                    null,
                    $event->getExtra()
                ));
            }
        } catch (ConnectException $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, $exception->getMessage(), $event->getAction()));
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, $exception->getMessage(), $event->getAction()));
        } finally {
            return true;
        }
    }
}
