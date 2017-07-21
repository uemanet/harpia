<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class UsuarioListener
{
    private $pessoaRepository;
    private $ambienteVirtualRepository;

    public function __construct(PessoaRepository $pessoaRepository, AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_update_user') {
                return $this->update($sincronizacaoEvent);
            }
        } catch (ConnectException $exception) {
            flash()->error('Falha ao tentar sincronizar com o ambiente');
            // Interrompe a propagacao do evento
            return false;
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            return false;
        }
    }

    /**
     * Atualiza os dados de um usuario no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function update(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $pessoa = $this->pessoaRepository->find($sync->sym_table_id);

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($sync->sym_extra);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_update_user';
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

            event(new AtualizarSyncEvent($pessoa, $status, $response['message'], $param['action'], null, $sync->sym_extra));
            return true;
        }

        return false;
    }
}
