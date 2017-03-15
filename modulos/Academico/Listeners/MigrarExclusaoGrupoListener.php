<?php

namespace Modulos\Academico\Listeners;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Events\DeleteGrupoEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarExclusaoGrupoListener
{
    protected $sincronizacaoRepository;
    protected $grupoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository,
                                GrupoRepository $grupoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeleteGrupoEvent $event)
    {
        $gruposMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_grupos',
            'sym_status' => 1,
            'sym_action' => 'DELETE'
        ]);

        if ($gruposMigrar->count()) {
            foreach ($gruposMigrar as $reg) {
                $grupo = $this->grupoRepository->find($reg->sym_table_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

                if ($ambiente) {
                    $param = [];

                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_delete_group';
                    $param['action'] = 'DELETE';

                    $param['data']['group']['grp_id'] = $grupo->grp_id;

                    $moodleSync = new Moodle();

                    $retorno = $moodleSync->send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }

                    event(new AtualizarSyncEvent($grupo, $status, $retorno['message'], $event->getAction()));
                }
            }
        }
    }
}
