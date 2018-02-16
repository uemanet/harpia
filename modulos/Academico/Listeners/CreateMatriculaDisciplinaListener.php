<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Events\CreateMatriculaDisciplinaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class CreateMatriculaDisciplinaListener
{
    protected $alunoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->alunoRepository = $alunoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(CreateMatriculaDisciplinaEvent $event)
    {
        try {
            // busca a matricula na oferta de disciplina
            $matriculaOfertaDisciplina = $event->getData();

            // pega a matricula do aluno no curso
            $matriculaCurso = $matriculaOfertaDisciplina->matriculaCurso;

            // ambiente virtual vinculado à turma do aluno
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaCurso->mat_trm_id);

            if (!$ambiente) {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->integracao();

            if ($ambServico) {
                $param = [];

                // url do ambiente
                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['functionname'] = $event->getEndpoint();
                $param['action'] = 'CREATE';

                $param['data']['enrol']['mof_id'] = $matriculaOfertaDisciplina->mof_id;

                // pega as informações do aluno
                $aluno = $this->alunoRepository->find($matriculaCurso->mat_alu_id);

                $param['data']['enrol']['pes_id'] = $aluno->alu_pes_id;
                $param['data']['enrol']['ofd_id'] = $matriculaOfertaDisciplina->mof_ofd_id;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new UpdateSincronizacaoEvent($matriculaOfertaDisciplina, $status, $response['message']));
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
