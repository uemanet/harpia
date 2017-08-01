<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\TutorVinculadoEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Moodle;

class MigrarTutorVinculadoListener
{
    protected $pessoaRepository;
    protected $ambienteVirtualRepository;
    protected $tutorGrupoRepository;
    protected $sincronizacaoRepository;
    protected $tutorRepository;
    protected $grupoRepository;

    public function __construct(PessoaRepository $pessoaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                TutorGrupoRepository $tutorGrupoRepository,
                                SincronizacaoRepository $sincronizacaoRepository,
                                GrupoRepository $grupoRepository,
                                TutorRepository $tutorRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->tutorRepository = $tutorRepository;
        $this->grupoRepository = $grupoRepository;
    }

    public function handle(TutorVinculadoEvent $event)
    {
        $tutoresMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_tutores_grupos',
            'sym_status' => 1,
            'sym_action' => "CREATE"
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

                $name = explode(" ", $pessoa->pes_nome);
                $firstName = array_shift($name);
                $lastName = implode(" ", $name);

                $data['tutor']['ttg_tipo_tutoria'] = $this->tutorGrupoRepository->getTipoTutoria($tutor->tut_id, $grupo->grp_id);
                $data['tutor']['grp_id'] = $grupo->grp_id;
                $data['tutor']['pes_id'] = $tutor->tut_pes_id;
                $data['tutor']['firstname'] = $firstName;
                $data['tutor']['lastname'] = $lastName;
                $data['tutor']['email'] = $pessoa->pes_email;
                $data['tutor']['username'] = $pessoa->pes_email;
                $data['tutor']['password'] = "changeme";
                $data['tutor']['city'] = "São Luís";

                $param['url'] = $ambiente->url;
                $param['token'] = $ambiente->token;
                $param['action'] = 'post';
                $param['functioname'] = 'local_integracao_enrol_tutor';
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($tutorGrupo, $status, $response['message']));
            }
        }
    }
}
