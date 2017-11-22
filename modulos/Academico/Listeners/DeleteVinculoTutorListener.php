<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Academico\Events\DeleteVinculoTutorEvent;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class DeleteVinculoTutorListener
{
    protected $tutorRepository;
    protected $grupoRepository;
    protected $pessoaRepository;
    protected $tutorGrupoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        TutorRepository $tutorRepository,
        GrupoRepository $grupoRepository,
        PessoaRepository $pessoaRepository,
        TutorGrupoRepository $tutorGrupoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->grupoRepository = $grupoRepository;
        $this->tutorRepository = $tutorRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(DeleteVinculoTutorEvent $event)
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

            // Web service de integracao
            $ambServico = $ambiente->ambienteservico->first();

            if ($ambServico) {
                $pessoa = $this->pessoaRepository->find($tutor->tut_pes_id);

                $data['tutor']['pes_id'] = $pessoa->pes_id;
                $data['tutor']['grp_id'] = $grupo->grp_id;

                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['action'] = 'post';
                $param['functioname'] = $event->getEndpoint();
                $param['data'] = $data;

                $response = Moodle::send($param);
                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($tutorGrupo, $status, $response['message'], 'DELETE'));
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
