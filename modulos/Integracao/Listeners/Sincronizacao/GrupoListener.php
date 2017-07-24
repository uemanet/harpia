<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Integracao\Events\AtualizarSyncDeleteEvent;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class GrupoListener
{
    private $grupoRepository;
    private $cursoRepository;
    private $periodoLetivoRepository;
    private $ambienteVirtualRepository;

    public function __construct(GrupoRepository $grupoRepository,
                                CursoRepository $cursoRepository,
                                PeriodoLetivoRepository $periodoLetivoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->grupoRepository = $grupoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_create_group') {
                return $this->create($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_update_group') {
                return $this->update($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_delete_group') {
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
     * Cria um novo grupo no ambiente
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function create(SincronizacaoEvent $sincronizacaoEvent)
    {
        $grupo = $this->grupoRepository->find($sincronizacaoEvent->getData()->sym_table_id);

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_create_group';
            $param['action'] = 'CREATE';

            $param['data']['group']['trm_id'] = $grupo->grp_trm_id;
            $param['data']['group']['grp_id'] = $grupo->grp_id;
            $param['data']['group']['name'] = $grupo->grp_nome;
            $param['data']['group']['description'] = '';

            $response = Moodle::send($param);

            $status = 3;

            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }

            event(new AtualizarSyncEvent($grupo, $status, $response['message']));
            return true;
        }

        return false;
    }

    /**
     * Atualiza um grupo no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function update(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $grupo = $this->grupoRepository->find($sync->sym_table_id);

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_update_group';
            $param['action'] = 'UPDATE';

            $param['data']['group']['grp_id'] = $grupo->grp_id;
            $param['data']['group']['grp_nome'] = $grupo->grp_nome;

            $response = Moodle::send($param);

            $status = 3;

            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }

            event(new AtualizarSyncEvent($grupo, $status, $response['message'], 'UPDATE'));
            return true;
        }

        return false;
    }

    /**
     * Exclui um grupo do ambiente
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function delete(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        // ambiente virtual vinculado à turma do grupo
        $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($sync->sym_extra);

        if ($ambiente) {
            $param = [];

            // url do ambiente
            $param['url'] = $ambiente->url;
            $param['token'] = $ambiente->token;
            $param['functioname'] = 'local_integracao_delete_group';
            $param['action'] = 'DELETE';

            $param['data']['group']['grp_id'] = $sync->sym_table_id;

            $response = Moodle::send($param);

            $status = 3;

            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }

            event(new AtualizarSyncDeleteEvent($sync->sym_table,
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
