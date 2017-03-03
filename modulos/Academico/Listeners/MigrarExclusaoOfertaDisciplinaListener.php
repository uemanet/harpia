<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\DeleteOfertaDisciplinaEvent;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarExclusaoOfertaDisciplinaListener
{
    protected $sincronizacaoRepository;
    protected $ofertaDisciplinaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeleteOfertaDisciplinaEvent $event)
    {
        $ofertasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_ofertas_disciplinas',
            'sym_status' => 1,
            'sym_action' => "DELETE"
        ]);

        if ($ofertasMigrar->count()) {
            foreach ($ofertasMigrar as $item) {
                $oferta = $this->ofertaDisciplinaRepository->find($item->sym_table_id);
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($oferta->ofd_trm_id);

                if (!$ambiente) {
                    continue;
                }

                $data['ofd_id'] = $oferta->ofd_id;
                $data['trm_id'] = $oferta->ofd_trm_id;

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'post';
                $param['functioname'] = 'local_integracao_delete_discipline';
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new AtualizarSyncEvent($oferta, $status, $response['message']));
            }
        }
    }
}
