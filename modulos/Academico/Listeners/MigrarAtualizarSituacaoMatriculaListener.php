<?php

namespace Modulos\Academico\Listeners;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Events\AtualizarSituacaoMatriculaEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarAtualizarSituacaoMatriculaListener
{
    protected $sincronizacaoRepository;
    protected $matriculaCursoRepository;
    protected $ambienteVirtualRepository;
    protected $pessoaRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(AtualizarSituacaoMatriculaEvent $event)
    {
        $matriculaTurmaMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_matriculas',
            'sym_status' => 1,
            'sym_action' => "UPDATE_SITUACAO_MATRICULA"
        ]);

        if ($matriculaTurmaMigrar->count()) {
            foreach ($matriculaTurmaMigrar as $reg) {
                $matriculaTurma = $this->matriculaCursoRepository->find($reg->sym_table_id);

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

                    $moodle = new Moodle();
                    $retorno = $moodle->send($param);

                    $status = 3;
                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }
                    event(new UpdateSincronizacaoEvent($matriculaTurma, $status, $retorno['message'], $event->getAction()));
                }
            }
        }
    }
}
