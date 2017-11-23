<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Events\CreateMatriculaTurmaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class CreateMatriculaTurmaListener
{
    protected $alunoRepository;
    protected $pessoaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        PessoaRepository $pessoaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->alunoRepository = $alunoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(CreateMatriculaTurmaEvent $event)
    {
        try {
            $matriculaTurma = $event->getData();

            $aluno = $this->alunoRepository->find($matriculaTurma->mat_alu_id);
            $pessoa = $this->pessoaRepository->find($aluno->alu_pes_id);

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaTurma->mat_trm_id);

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

                $nome = explode(" ", $pessoa->pes_nome);
                $firstName = array_shift($nome);
                $lastName = implode(" ", $nome);

                $param['data']['student']['trm_id'] = $matriculaTurma->mat_trm_id;
                $param['data']['student']['mat_id'] = $matriculaTurma->mat_id;
                if ($matriculaTurma->mat_grp_id) {
                    $param['data']['student']['grp_id'] = $matriculaTurma->mat_grp_id;
                }
                $param['data']['student']['pes_id'] = $pessoa->pes_id;
                $param['data']['student']['firstname'] = $firstName;
                $param['data']['student']['lastname'] = $lastName;
                $param['data']['student']['email'] = $pessoa->pes_email;
                $param['data']['student']['username'] = $pessoa->pes_email;
                $param['data']['student']['password'] = 'changeme';
                $param['data']['student']['city'] = $pessoa->pes_cidade;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent($matriculaTurma, $status, $response['message']));
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
