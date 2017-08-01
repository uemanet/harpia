<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\DeleteTutorVinculadoEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
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
                                PessoaRepository $pessoaRepository,
                                TutorRepository $tutorRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->tutorRepository = $tutorRepository;
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

                $data['tutor']['pes_id'] = $pessoa->pes_id;
                $data['tutor']['grp_id'] = $grupo->grp_id;

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'post';
                $param['functioname'] = 'local_integracao_unenrol_tutor_group';
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($tutorGrupo, $status, $response['message'], $event->getAction()));
            }
        }
    }
}
