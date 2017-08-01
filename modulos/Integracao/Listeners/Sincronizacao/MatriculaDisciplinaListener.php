<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;

class MatriculaDisciplinaListener
{
    private $turmaRepository;
    private $alunoRepository;
    private $pessoaRepository;
    private $ambienteVirtualRepository;
    private $matriculaOfertaDisciplinaRepository;

    public function __construct(PessoaRepository $pessoaRepository,
                                AlunoRepository $alunoRepository,
                                TurmaRepository $turmaRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository)
    {
        $this->turmaRepository = $turmaRepository;
        $this->alunoRepository = $alunoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_enrol_student_discipline') {
                return $this->create($sincronizacaoEvent);
            }
        } catch (ConnectException $exception) {
            flash()->error('Falha ao tentar sincronizar com o ambiente');

            // Mantem a propagacao do evento
            return true;
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            // Mantem a propagacao do evento
            return true;
        }
    }

    /**
     * Matricula o aluno em uma disciplina no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function create(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        // busca a matricula na oferta de disciplina
        $matriculaOfertaDisciplina = $this->matriculaOfertaDisciplinaRepository->find($sync->sym_table_id);

        // pega a matricula do aluno no curso
        $matriculaCurso = $matriculaOfertaDisciplina->matriculaCurso;

        // ambiente virtual vinculado à turma do aluno
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaCurso->mat_trm_id);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_enrol_student_discipline';
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
            return true;
        }

        return false;
    }
}
