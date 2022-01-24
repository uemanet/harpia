<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Events\CreateOfertaDisciplinaEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;

class CreateOfertaDisciplinaV2Listener
{
    protected $pessoaRepository;
    protected $professorRepository;
    protected $disciplinaRepository;
    protected $sincronizacaoRepository;
    protected $ambienteVirtualRepository;
    protected $moduloDisciplinaRepository;

    public function __construct(
        PessoaRepository $pessoaRepository,
        ProfessorRepository $professorRepository,
        DisciplinaRepository $disciplinaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository,
        ModuloDisciplinaRepository $moduloDisciplinaRepository
    ) {
        $this->pessoaRepository = $pessoaRepository;
        $this->professorRepository = $professorRepository;
        $this->disciplinaRepository = $disciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->moduloDisciplinaRepository = $moduloDisciplinaRepository;
    }

    public function handle(CreateOfertaDisciplinaEvent $event)
    {
        try {
            $oferta = $event->getData();

            // ambiente virtual vinculado à turma do grupo
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($oferta->ofd_trm_id);

            if (!$ambiente) {
                return;
            }

            if ($oferta->turma->trm_tipo_integracao != 'v2') {
                return;
            }

            // Web service de integracao
            $ambServico = $ambiente->integracaoV2();

            if ($ambServico) {
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

                $data['discipline']['category'] = 1;
                $data['discipline']['shortname'] = $oferta->moduloDisciplina->disciplina->dis_nome.' '.$oferta->ofd_id;
                $data['discipline']['fullname'] = $oferta->moduloDisciplina->disciplina->dis_nome.' '.$oferta->ofd_id;
                $data['discipline']['summaryformat'] = 1;
                $data['discipline']['format'] = 'topics';
                $data['discipline']['numsections'] = 0;

                $data['discipline']['per_nome'] = $oferta->periodoLetivo->per_nome;
                $data['discipline']['per_id'] = (int)$oferta->ofd_per_id;
                $data['discipline']['trm_id'] = (int)$oferta->ofd_trm_id;
                $data['discipline']['crs_id'] = (int)$oferta->turma->ofertaCurso->curso->crs_id;
                $data['discipline']['ofd_id'] = (int)$oferta->ofd_id;
                $data['discipline']['teacher'] = $teacher;

                $moduloDisciplina = $this->moduloDisciplinaRepository->find($oferta->ofd_mdc_id);
                $disciplina = $this->disciplinaRepository->find($moduloDisciplina->mdc_dis_id);

                $data['discipline']['name'] = $disciplina->dis_nome;

                $param['url'] = $ambiente->amb_url;
                $param['token'] = $ambServico->asr_token;
                $param['action'] = 'post';
                $param['functionname'] = $event->getEndpointV2();
                $param['data'] = $data;

                $response = Moodle::send($param);

                $status = 3;

                if (array_key_exists('status', $response) && $response['status'] == 'success') {
                    $status = 2;
                }

                event(new UpdateSincronizacaoEvent($oferta, $status, $response['message']));
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
