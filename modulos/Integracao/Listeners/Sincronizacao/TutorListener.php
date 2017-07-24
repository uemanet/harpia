<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class TutorListener
{
    private $tutorRepository;
    private $grupoRepository;
    private $pessoaRepository;
    private $tutorGrupoRepository;
    private $ambienteVirtualRepository;

    public function __construct(GrupoRepository $grupoRepository,
                                TutorRepository $tutorRepository,
                                PessoaRepository $pessoaRepository,
                                TutorGrupoRepository $tutorGrupoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->tutorRepository = $tutorRepository;
        $this->grupoRepository = $grupoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_enrol_tutor') {
                return $this->create($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_unenrol_tutor_group') {
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
     * Vincula um tutor a um grupo no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function create(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();
        $tutorGrupo = $this->tutorGrupoRepository->find($sync->sym_table_id);
        $tutor = $this->tutorRepository->find($tutorGrupo->ttg_tut_id);
        $grupo = $this->grupoRepository->find($tutorGrupo->ttg_grp_id);

        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

        if ($ambiente) {
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

            event(new AtualizarSyncEvent($tutorGrupo, $status, $response['message']));
            return true;
        }

        return false;
    }

    /**
     * Remove um tutor de um grupo no Moodle
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function delete(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();
        $tutorGrupo = $this->tutorGrupoRepository->find($sync->sym_table_id);
        $tutor = $this->tutorRepository->find($tutorGrupo->ttg_tut_id);
        $grupo = $this->grupoRepository->find($tutorGrupo->ttg_grp_id);

        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

        if ($ambiente) {
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

            event(new AtualizarSyncEvent($tutorGrupo, $status, $response['message'], 'DELETE'));
            return true;
        }

        return false;
    }
}
