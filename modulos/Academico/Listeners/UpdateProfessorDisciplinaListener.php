<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Academico\Events\UpdateProfessorDisciplinaEvent;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class UpdateProfessorDisciplinaListener
{
    protected $sincronizacaoRepository;
    protected $ofertaDisciplinaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(UpdateProfessorDisciplinaEvent $event)
    {
        $ofertaDisciplina = $event->getData();

        try {
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($ofertaDisciplina->ofd_trm_id);

            if ($ambiente) {
                $param = [];

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['functioname'] = $event->getEndpoint();
                $param['action'] = "UPDATE";

                $nome = explode(" ", $ofertaDisciplina->professor->pessoa->pes_nome);
                $firstName = array_shift($nome);
                $lastName = implode(" ", $nome);

                $teacher = [
                    'pes_id' => $ofertaDisciplina->professor->prf_pes_id,
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'email' => $ofertaDisciplina->professor->pessoa->pes_email,
                    'username' => $ofertaDisciplina->professor->pessoa->pes_email,
                    'password' => 'changeme',
                    'city' => $ofertaDisciplina->professor->pessoa->pes_cidade
                ];


                $discipline = [
                    'ofd_id' => $ofertaDisciplina->ofd_id,
                    'teacher' => $teacher
                ];

                $param['data']['discipline'] = $discipline;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent(
                    $ofertaDisciplina,
                    $status,
                    $response['message'],
                    $event->getAction()
                ));
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
