<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Academico\Events\CreateVinculoTutorEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class CreateVinculoTutorListener
{
    protected $tutorRepository;
    protected $grupoRepository;
    protected $pessoaRepository;
    protected $tutorGrupoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        GrupoRepository $grupoRepository,
        TutorRepository $tutorRepository,
        PessoaRepository $pessoaRepository,
        TutorGrupoRepository $tutorGrupoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->tutorRepository = $tutorRepository;
        $this->grupoRepository = $grupoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(CreateVinculoTutorEvent $event)
    {
        try {
            $tutorGrupo = $event->getData();
            $tutor = $this->tutorRepository->find($tutorGrupo->ttg_tut_id);
            $grupo = $this->grupoRepository->find($tutorGrupo->ttg_grp_id);
            
            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

            if (!$ambiente) {
                return;
            }

            if ($grupo->turma->trm_tipo_integracao != 'v1') {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->integracao();

            if ($ambServico) {
                $pessoa = $this->pessoaRepository->find($tutor->tut_pes_id);

                $name = explode(" ", $pessoa->pes_nome);
                $firstName = array_shift($name);
                $lastName = implode(" ", $name);

                $data['tutor']['ttg_tipo_tutoria'] = $tutorGrupo->getOriginal('ttg_tipo_tutoria');

                $data['tutor']['grp_id'] = $grupo->grp_id;
                $data['tutor']['pes_id'] = $tutor->tut_pes_id;
                $data['tutor']['firstname'] = $firstName;
                $data['tutor']['lastname'] = $lastName;
                $data['tutor']['email'] = $pessoa->pes_email;
                $data['tutor']['username'] = $pessoa->pes_email;
                $data['tutor']['password'] = "changeme";
                $data['tutor']['city'] = "São Luís";

                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['action'] = 'post';
                $param['functionname'] = $event->getEndpoint();
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($tutorGrupo, $status, $response['message']));
            }
        } catch (ConnectException | ClientException | \Exception $exception) {
            if (env('app.debug')) {
                throw $exception;
            }

            event(new UpdateSincronizacaoEvent($event->getData(), 3, get_class($exception), $event->getAction()));
        } finally {
            return true;
        }
    }
}
