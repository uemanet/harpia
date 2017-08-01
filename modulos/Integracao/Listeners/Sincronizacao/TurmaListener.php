<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Events\DeleteSincronizacaoEvent;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class TurmaListener
{
    private $turmaRepository;
    private $cursoRepository;
    private $periodoLetivoRepository;
    private $ambienteVirtualRepository;

    public function __construct(TurmaRepository $turmaRepository,
                                CursoRepository $cursoRepository,
                                PeriodoLetivoRepository $periodoLetivoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_create_course') {
                return $this->create($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_update_course') {
                return $this->update($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_delete_course') {
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
     * Cria uma turma no ambiente
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function create(SincronizacaoEvent $sincronizacaoEvent)
    {
        $turma = $this->turmaRepository->find($sincronizacaoEvent->getData()->sym_table_id);
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

        if (!$ambiente) {
            return false;
        }

        $data['course']['trm_id'] = $turma->trm_id;
        $data['course']['category'] = 1;
        $data['course']['shortname'] = $this->turmaRepository->shortName($turma);
        $data['course']['fullname'] = $this->turmaRepository->fullName($turma);
        $data['course']['summaryformat'] = 1;
        $data['course']['format'] = 'topics';
        $data['course']['numsections'] = 0;


        $param['url'] = $ambiente->url;
        $param['token'] = $ambiente->token;
        $param['action'] = 'post';
        $param['functioname'] = 'local_integracao_create_course';
        $param['data'] = $data;

        $response = Moodle::send($param);
        $status = 3;

        if (array_key_exists('status', $response) && $response['status'] == 'success') {
            $status = 2;
        }

        event(new UpdateSincronizacaoEvent($turma, $status, $response['message']));
        return true;
    }

    /**
     * Atualiza uma turma no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function update(SincronizacaoEvent $sincronizacaoEvent)
    {
        $turma = $this->turmaRepository->find($sincronizacaoEvent->getData()->sym_table_id);
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);

        if (!$ambiente) {
            return false;
        }

        if ($ambiente) {
            $data['course']['trm_id'] = $turma->trm_id;
            $data['course']['shortname'] = $this->turmaRepository->shortName($turma);
            $data['course']['fullname'] = $this->turmaRepository->fullName($turma);

            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['action'] = 'UPDATE';
            $param['functioname'] = 'local_integracao_update_course';
            $param['data'] = $data;

            $response = Moodle::send($param);
            $status = 3;

            if (array_key_exists('status', $response) && $response['status'] == 'success') {
                $status = 2;
            }

            event(new UpdateSincronizacaoEvent($turma, $status, $response['message'], $param['action']));
            return true;
        }
    }

    /**
     * Exclui uma turma do ambiente
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function delete(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $ambiente = $this->ambienteVirtualRepository
            ->getAmbienteWithTokenWhithoutTurma($sync->sym_extra);

        if ($ambiente) {
            $data['course']['trm_id'] = $sync->sym_table_id;

            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['action'] = 'post';
            $param['functioname'] = 'local_integracao_delete_course';
            $param['data'] = $data;

            $response = Moodle::send($param);
            $status = 3;

            if (array_key_exists('status', $response) && $response['status'] == 'success') {
                $status = 2;
            }

            event(new DeleteSincronizacaoEvent($sync->sym_table,
                $sync->sym_table_id,
                $status,
                $response['message'],
                'DELETE',
                null,
                $sync->sym_extra
            ));

            return true;
        }

        return false;
    }
}
