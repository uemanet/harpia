<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\DeleteOfertaDisciplinaEvent;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\AtualizarSyncDeleteEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Moodle;

class MigrarExclusaoOfertaDisciplinaListener
{
    protected $sincronizacaoRepository;
    protected $ofertaDisciplinaRepository;
    protected $ambienteVirtualRepository;
    protected $professorRepository;
    protected $pessoaRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                ProfessorRepository $professorRepository,
                                PessoaRepository $pessoaRepository)
    {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->professorRepository = $professorRepository;
        $this->pessoaRepository = $pessoaRepository;
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
                $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($item->sym_extra);

                if ($ambiente) {
                    $data['discipline']['ofd_id'] = $item->sym_table_id;

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

                    event(new AtualizarSyncDeleteEvent($item->sym_table,
                                                     $item->sym_table_id,
                                                     $status,
                                                     $response['message'],
                                                     $event->getAction(),
                                                     null,
                                                     $item->sym_extra
                                                     ));
                }
            }
        }
    }
}
