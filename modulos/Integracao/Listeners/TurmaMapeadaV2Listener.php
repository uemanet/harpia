<?php

namespace Modulos\Integracao\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class TurmaMapeadaV2Listener
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
    )
    {
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
    }

    public function handle(TurmaMapeadaEvent $event)
    {
        try {
            $turma = $event->getData();

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

            if (!$ambiente) {
                return;
            }

            if ($turma->trm_tipo_integracao != 'v2') {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->integracaoV2();

            if ($ambServico) {
                $data['course']['trm_id'] = $turma->trm_id;
                $data['course']['per_id'] = $turma->periodo->per_id;
                $data['course']['crs_id'] = $turma->ofertacurso->curso->crs_id;
                $data['course']['crs_nome'] = $turma->ofertacurso->curso->crs_nome;
                $data['course']['per_nome'] = $turma->periodo->per_nome;

                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['action'] = 'post';
                $param['functionname'] = $event->getEndpointV2();
                $param['data'] = $data;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($turma, $status, $response['message']));
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
