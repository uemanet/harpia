<?php

namespace Modulos\Integracao\Listeners;

use Modulos\Integracao\Events\DeleteSincronizacaoEvent;
use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\TurmaRemovidaEvent;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class TurmaRemovidaListener
{
    private $turmaRepository;
    private $cursoRepository;
    private $periodoLetivoRepository;
    private $ambienteVirtualRepository;
    private $sincronizacaoRepository;

    public function __construct(
        TurmaRepository $turmaRepository,
        CursoRepository $cursoRepository,
        PeriodoLetivoRepository $periodoLetivoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository,
        SincronizacaoRepository $sincronizacaoRepository
    ) {
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(TurmaRemovidaEvent $event)
    {
        try {
            $sync = $event->getData();

            $ambiente = $this->ambienteVirtualRepository
                ->getAmbienteWithTokenWhithoutTurma($sync->sym_extra);

            if ($ambiente) {
                $data['course']['trm_id'] = $sync->sym_table_id;

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
                    $sync->sym_table,
                    $sync->sym_table_id,
                    $status,
                    $response['message'],
                    'DELETE',
                    null,
                    $sync->sym_extra
                ));
            }
        } catch (ConnectException $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            return true;
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            // Mantem a propagacao do evento
            return true;
        }
    }
}
