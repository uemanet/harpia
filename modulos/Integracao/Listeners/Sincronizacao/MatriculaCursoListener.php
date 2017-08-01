<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;

class MatriculaCursoListener
{
    private $turmaRepository;
    private $alunoRepository;
    private $pessoaRepository;
    private $matriculaCursoRepository;
    private $ambienteVirtualRepository;

    public function __construct(PessoaRepository $pessoaRepository,
                                AlunoRepository $alunoRepository,
                                TurmaRepository $turmaRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->turmaRepository = $turmaRepository;
        $this->alunoRepository = $alunoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_enrol_student') {
                return $this->create($sincronizacaoEvent);
            }


            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_change_role_student_course') {
                return $this->update($sincronizacaoEvent);
            }
        } catch (ConnectException $exception) {
            flash()->error('Falha ao tentar sincronizar com o ambiente');

            // Mantem a propagacao do evento
            return true;
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            return true;
        }
    }

    /**
     * Matricula o aluno em uma turma no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function create(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $matriculaTurma = $this->matriculaCursoRepository->find($sync->sym_table_id);
        $aluno = $this->alunoRepository->find($matriculaTurma->mat_alu_id);
        $pessoa = $this->pessoaRepository->find($aluno->alu_pes_id);

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaTurma->mat_trm_id);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_enrol_student';
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
            return true;
        }

        return false;
    }

    /**
     * Atualiza o status da matricula do aluno
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function update(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $matriculaTurma = $this->matriculaCursoRepository->find($sync->sym_table_id);

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaTurma->mat_trm_id);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_change_role_student_course';
            $param['action'] = 'UPDATE_SITUACAO_MATRICULA';

            $param['data']['student']['trm_id'] = $matriculaTurma->mat_trm_id;
            $param['data']['student']['pes_id'] = $matriculaTurma->aluno->alu_pes_id;
            $param['data']['student']['new_status'] = $matriculaTurma->mat_situacao;

            $response = Moodle::send($param);

            $status = 3;
            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }
            event(new UpdateSincronizacaoEvent($matriculaTurma, $status, $response['message'], 'UPDATE_SITUACAO_MATRICULA'));
            return true;
        }

        return false;
    }
}
