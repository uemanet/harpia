<?php

namespace Modulos\Geral\Listeners;

use Harpia\Event\Event;
use Harpia\Moodle\Moodle;
use Modulos\Geral\Events\AtualizarPessoaEvent;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Geral\Repositories\PessoaRepository;

class MigrarAtualizarPessoaListener
{
    protected $sincronizacaoRepository;
    protected $ambienteVirtualRepository;
    protected $pessoaRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        PessoaRepository $pessoaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(AtualizarPessoaEvent $event)
    {
        $pessoasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'gra_pessoas',
            'sym_status' => 1,
            'sym_action' => "UPDATE"
        ]);

        // dd($pessoasMigrar);

        if ($pessoasMigrar->count()) {
            foreach ($pessoasMigrar as $reg) {
                $pessoa = $this->pessoaRepository->find($reg->sym_table_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($reg->sym_extra);
                // dd($ambiente);
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
                    $moodleSync = new Moodle();

                    $retorno = $moodleSync->send($param);


                    $status = 3;

                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }

                    event(new AtualizarSyncEvent($pessoa, $status, $retorno['message'],$param['action'],null,$reg->sym_extra));
                }
            }
        }
    }
}
