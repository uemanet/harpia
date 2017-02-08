<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\OfertaDisciplinaEvent;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Moodle;

class MigrarOfertaDisciplinaListener
{
    protected $ambienteVirtualRepository;
    protected $professorRepository;
    protected $pessoaRepository;
    protected $moduloDisciplinaRepository;
    protected $disciplinaRepository;
    protected $sincronizacaoRepository;
    protected $ofertaDisciplinaRepository;

    public function __construct(PessoaRepository $pessoaRepository,
                                ProfessorRepository $professorRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                ModuloDisciplinaRepository $moduloDisciplinaRepository,
                                DisciplinaRepository $disciplinaRepository,
                                SincronizacaoRepository $sincronizacaoRepository,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->professorRepository = $professorRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->moduloDisciplinaRepository = $moduloDisciplinaRepository;
        $this->disciplinaRepository = $disciplinaRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
    }

    public function handle(OfertaDisciplinaEvent $event)
    {
        $ofertasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_ofertas_disciplinas',
            'sym_status' => 1
        ]);

        if ($ofertasMigrar->count()) {
            foreach ($ofertasMigrar as $item) {
                $oferta = $this->ofertaDisciplinaRepository->find($item->sym_table_id);
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($oferta->ofd_trm_id);

                if (!$ambiente) {
                    continue;
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

                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $status = 2;
                    }
                }

                event(new AtualizarSyncEvent($oferta, $status, $response['message']));
            }
        }
    }
}
