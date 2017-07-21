<?php

namespace Modulos\Integracao\Listeners\Sincronizacao;

use Moodle;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\SincronizacaoEvent;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Events\AtualizarSyncDeleteEvent;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;

class DisciplinaListener
{
    protected $pessoaRepository;
    protected $professorRepository;
    protected $disciplinaRepository;
    protected $ambienteVirtualRepository;
    protected $ofertaDisciplinaRepository;
    protected $moduloDisciplinaRepository;

    public function __construct(PessoaRepository $pessoaRepository,
                                ProfessorRepository $professorRepository,
                                DisciplinaRepository $disciplinaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                ModuloDisciplinaRepository $moduloDisciplinaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->professorRepository = $professorRepository;
        $this->disciplinaRepository = $disciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->moduloDisciplinaRepository = $moduloDisciplinaRepository;
    }

    public function handle(SincronizacaoEvent $sincronizacaoEvent)
    {
        try {
            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_create_discipline') {
                return $this->create($sincronizacaoEvent);
            }

            if ($sincronizacaoEvent->getMoodleFunction() == 'local_integracao_delete_discipline') {
                return $this->delete($sincronizacaoEvent);
            }
        } catch (ConnectException $exception) {
            flash()->error('Falha ao tentar sincronizar com o ambiente');
            // Interrompe a propagacao do evento
            return false;
        } catch (\Exception $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            return false;
        }
    }

    /**
     * Cria um nova disciplina no ambiente
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function create(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();
        $oferta = $this->ofertaDisciplinaRepository->find($sync->sym_table_id);
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($oferta->ofd_trm_id);

        if (!$ambiente) {
            return false;
        }

        $professor = $this->professorRepository->find($oferta->ofd_prf_id);
        $pessoa = $this->pessoaRepository->find($professor->prf_pes_id);

        $name = explode(" ", $pessoa->pes_nome);
        $firstName = array_shift($name);
        $lastName = implode(" ", $name);

        $teacher['pes_id'] = $pessoa->pes_id;
        $teacher['firstname'] = $firstName;
        $teacher['lastname'] = $lastName;
        $teacher['email'] = $pessoa->pes_email;
        $teacher['username'] = $pessoa->pes_email;
        $teacher['password'] = "changeme";
        $teacher['city'] = $pessoa->pes_cidade;

        $data['discipline']['trm_id'] = $oferta->ofd_trm_id;
        $data['discipline']['ofd_id'] = $oferta->ofd_id;
        $data['discipline']['teacher'] = $teacher;

        $moduloDisciplina = $this->moduloDisciplinaRepository->find($oferta->ofd_mdc_id);
        $disciplina = $this->disciplinaRepository->find($moduloDisciplina->mdc_dis_id);

        $data['discipline']['name'] = $disciplina->dis_nome;

        $param['url'] = $ambiente->url;
        $param['token'] = $ambiente->token;
        $param['action'] = 'post';
        $param['functioname'] = 'local_integracao_create_discipline';
        $param['data'] = $data;

        $response = Moodle::send($param);
        $status = 3;

        if (array_key_exists('status', $response) && $response['status'] == 'success') {
            $status = 2;
        }

        event(new AtualizarSyncEvent($oferta, $status, $response['message']));
        return true;
    }

    /**
     * Exclui uma disciplina do ambiente
     * @param SincronizacaoEvent $sincronizacaoEvent
     * @return bool
     */
    private function delete(SincronizacaoEvent $sincronizacaoEvent)
    {
        $sync = $sincronizacaoEvent->getData();

        $oferta = $this->ofertaDisciplinaRepository->find($sync->sym_table_id);
        $ambiente = $this->ambienteVirtualRepository->getAmbienteWithToken($sync->sym_extra);

        if ($ambiente) {
            $data['discipline']['ofd_id'] = $sync->sym_table_id;

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

            event(new AtualizarSyncDeleteEvent(
                $sync->sym_table,
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
