<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class AlunoGrupoListener
{
    private $matriculaCursoRepository;
    private $ambienteVirtualRepository;

    public function __construct(MatriculaCursoRepository $matriculaCursoRepository, AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_change_student_group') {
                return $this->update($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_unenrol_student_group') {
                return $this->delete($sincronizacaoEvent);
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
     * Troca o grupo de um aluno no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function update(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $matricula = $this->matriculaCursoRepository->find($sync->sym_table_id);

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matricula->mat_trm_id);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_change_student_group';
            $param['action'] = 'UPDATE_GRUPO_ALUNO';

            $param['data']['student']['mat_id'] = $matricula->mat_id;
            $param['data']['student']['pes_id'] = $matricula->aluno->alu_pes_id;
            if ($sync->sym_extra) {
                $param['data']['student']['old_grp_id'] = $sync->sym_extra;
            }
            $param['data']['student']['new_grp_id'] = $matricula->mat_grp_id;

            $response = Moodle::send($param);

            $status = 3;

            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }
            event(new UpdateSincronizacaoEvent($matricula, $status, $response['message'], $param['action'], null, $sync->sym_extra));
            return true;
        }

        return false;
    }

    /**
     * Troca o grupo de um aluno no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function delete(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $matricula = $this->matriculaCursoRepository->find($sync->sym_table_id);

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
            $param['data']['student']['grp_id'] = $sync->sym_extra;

            $response = Moodle::send($param);

            $status = 3;

            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }

            event(new UpdateSincronizacaoEvent($matricula, $status, $response['message'], $param['action'], null, $sync->sym_extra));
            return true;
        }

        return false;
    }
}
