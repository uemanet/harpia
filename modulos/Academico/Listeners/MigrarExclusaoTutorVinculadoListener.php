<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\DeleteTutorVinculadoEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Moodle;

class MigrarExclusaoTutorVinculadoListener
{
    protected $sincronizacaoRepository;
    protected $ambienteVirtualRepository;
    protected $tutorGrupoRepository;
    protected $tutorRepository;
    protected $grupoRepository;
    protected $pessoaRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                TutorGrupoRepository $tutorGrupoRepository,
                                GrupoRepository $grupoRepository,
                                PessoaRepository $pessoaRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function handle(DeleteTutorVinculadoEvent $event)
    {
        $tutoresMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_tutores_grupos',
            'sym_status' => 1,
            'sym_action' => "DELETE"
        ]);

        if ($tutoresMigrar->count()) {
            foreach ($tutoresMigrar as $item) {
                $tutorGrupo = $this->tutorGrupoRepository->find($item->sym_table_id);
                $tutor = $this->tutorRepository->find($tutorGrupo->ttg_tut_id);
                $grupo = $this->grupoRepository->find($tutorGrupo->ttg_grp_id);

                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

                if (!$ambiente) {
                    continue;
                }

                $pessoa = $this->pessoaRepository->find($tutor->tut_pes_id);

                $data['data']['pes_id'] = $pessoa->pes_id;
                $data['data']['grp_id'] = $pessoa->pes_id;

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'post';
                // TODO adicionar end point correto (Ainda nao implementado no plugin de integracao)
                $param['functioname'] = 'local_integracao_enrol_tutor';
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new AtualizarSyncEvent($tutorGrupo, $status, $response['message']));
            }
        }
    }
}
