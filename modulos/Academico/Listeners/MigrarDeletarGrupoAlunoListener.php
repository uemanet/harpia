<?php

namespace Modulos\Academico\Listeners;

use Harpia\Event\Event;
use Modulos\Academico\Events\DeletarGrupoAlunoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Harpia\Moodle\Moodle;

class MigrarDeletarGrupoAlunoListener
{
    protected $sincronizacaoRepository;
    protected $matriculaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        MatriculaCursoRepository $matriculaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->matriculaRepository = $matriculaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeletarGrupoAlunoEvent $event)
    {
        $matriculasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_matriculas',
            'sym_status' => 1,
            'sym_action' => "DELETE_GRUPO_ALUNO"
        ]);

        if ($matriculasMigrar->count()) {
            foreach ($matriculasMigrar as $reg) {
                $matricula = $this->matriculaRepository->find($reg->sym_table_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matricula->mat_trm_id);

                if ($ambiente) {
                    $param = [];

                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_unenrol_student_group';
                    $param['action'] = 'DELETE_GRUPO_ALUNO';

                    $param['data']['student']['mat_id'] = $matricula->mat_id;
                    $param['data']['student']['pes_id'] = $matricula->aluno->alu_pes_id;
                    $param['data']['student']['grp_id'] = $reg->sym_extra;
                    //dd($param);
                    $moodleSync = new Moodle();

                    $retorno = $moodleSync->send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }
                    event(new UpdateSincronizacaoEvent($matricula, $status, $retorno['message'], $param['action'], null, $reg->sym_extra));
                }
            }
        }
    }
}
