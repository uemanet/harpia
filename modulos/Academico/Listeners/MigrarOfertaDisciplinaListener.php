<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\OfertaDisciplinaEvent;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Moodle;

class MigrarOfertaDisciplinaListener
{
    protected $ambienteVirtualRepository;
    protected $professorRepository;
    protected $pessoaRepository;
    protected $moduloDisciplinaRepository;
    protected $disciplinaRepository;

    public function __construct(PessoaRepository $pessoaRepository,
                                ProfessorRepository $professorRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                ModuloDisciplinaRepository $moduloDisciplinaRepository,
                                DisciplinaRepository $disciplinaRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->professorRepository = $professorRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->moduloDisciplinaRepository = $moduloDisciplinaRepository;
        $this->disciplinaRepository = $disciplinaRepository;
    }

    public function handle(OfertaDisciplinaEvent $event)
    {
        $oferta = $event->getData();

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
        $data['discipline']['pes_id'] = $pessoa->pes_id;
        $data['discipline']['teacher'] = $teacher;

        $moduloDisciplina = $this->moduloDisciplinaRepository->find($oferta->ofd_mdc_id);
        $disciplina = $this->disciplinaRepository->find($moduloDisciplina->mdc_dis_id);

        $data['discipline']['name'] = $disciplina->dis_nome;

        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($oferta->ofd_trm_id);

        if (!$ambiente) {
            // Encerra a function sem interromper a propagacao do evento
            return true;
        }

        $param['url'] = $ambiente->url;
        $param['token'] = $ambiente->token;
        $param['action'] = 'post';
        $param['functioname'] = 'local_integracao_create_discipline';
        $param['data'] = $data;

        $response = Moodle::send($param);
        $status = 3;

        if (array_key_exists('status', $response)) {
            if ($response['status'] == 'success') {
                $status = 2;
            }
        }

        event(new AtualizarSyncEvent($oferta, $status, $response['message']));
    }
}
