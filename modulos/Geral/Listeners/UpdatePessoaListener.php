<?php

namespace Modulos\Geral\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Events\UpdatePessoaEvent;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class UpdatePessoaListener
{
    protected $pessoaRepository;
    protected $sincronizacaoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        PessoaRepository $pessoaRepository,
        SincronizacaoRepository $sincronizacaoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->pessoaRepository = $pessoaRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(UpdatePessoaEvent $event)
    {
        try {
            $pessoa = $event->getData();

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->find($event->getExtra());

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
                $param['action'] = 'UPDATE';

                $nome = explode(" ", $pessoa->pes_nome);
                $firstName = array_shift($nome);
                $lastName = implode(" ", $nome);

                $param['data']['user']['pes_id'] = $pessoa->pes_id;
                $param['data']['user']['firstname'] = $firstName;
                $param['data']['user']['lastname'] = $lastName;
                $param['data']['user']['email'] = $pessoa->pes_email;
                $param['data']['user']['username'] = $pessoa->pes_email;
                $param['data']['user']['city'] = $pessoa->pes_cidade;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent(
                    $pessoa,
                    $status,
                    $response['message'],
                    $param['action'],
                    null,
                    $event->getExtra()
                ));
            }
        } catch (ConnectException | ClientException | \Exception $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, get_class($exception), $event->getAction()));
        }
    }
}
