<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\AlterarStatusMatriculaEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarAlteracaoStatusMatriculaListener
{
    private $sincronizacaoRepository;
    private $matriculaCursoRepository;
    private $ambienteVirtualRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(AlterarStatusMatriculaEvent $event)
    {
        $matriculasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_matriculas',
            'sym_status' => 1,
            'sym_action' => $event->getAction()
        ]);

        if ($matriculasMigrar->count()) {
            foreach ($matriculasMigrar as $reg) {
                $matricula = $this->matriculaCursoRepository->find($reg->sym_table_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matricula->mat_trm_id);

                if ($ambiente) {
                    $param = [];

                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_change_role_student_course';
                    $param['action'] = $event->getAction();

                    $student = [];
                    $student['trm_id'] = $matricula->mat_trm_id;
                    $student['pes_id'] = $matricula->aluno->alu_pes_id;
                    $student['new_status'] = $matricula->mat_situacao;

                    $param['data']['student'] = $student;

                    $moodle = new \Harpia\Moodle\Moodle();

                    $retorno = $moodle->send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno) && $retorno['status'] == 'success') {
                        $status = 2;
                    }

                    event(new AtualizarSyncEvent($matricula, $status, $retorno['message']));
                }
            }
        }
    }
}
