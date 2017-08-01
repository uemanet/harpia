<?php

namespace Modulos\Academico\Listeners;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Events\MatriculaAlunoTurmaEvent;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarMatriculaAlunoTurmaListener
{
    protected $sincronizacaoRepository;
    protected $matriculaCursoRepository;
    protected $ambienteVirtualRepository;
    protected $pessoaRepository;
    protected $alunoRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository,
        PessoaRepository $pessoaRepository,
        AlunoRepository $alunoRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->alunoRepository = $alunoRepository;
    }

    public function handle(MatriculaAlunoTurmaEvent $event)
    {
        $matriculaTurmaMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_matriculas',
            'sym_status' => 1,
            'sym_action' => "CREATE"
        ]);

        if ($matriculaTurmaMigrar->count()) {
            foreach ($matriculaTurmaMigrar as $reg) {
                $matriculaTurma = $this->matriculaCursoRepository->find($reg->sym_table_id);

                $aluno = $this->alunoRepository->find($matriculaTurma->mat_alu_id);

                $pessoa = $this->pessoaRepository->find($aluno->alu_pes_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaTurma->mat_trm_id);


                if ($ambiente) {
                    $param = [];

                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_enrol_student';
                    $param['action'] = 'CREATE';

                    $nome = explode(" ", $pessoa->pes_nome);
                    $firstName = array_shift($nome);
                    $lastName = implode(" ", $nome);

                    $param['data']['student']['trm_id'] = $matriculaTurma->mat_trm_id;
                    $param['data']['student']['mat_id'] = $matriculaTurma->mat_id;
                    if ($matriculaTurma->mat_grp_id) {
                        $param['data']['student']['grp_id'] = $matriculaTurma->mat_grp_id;
                    }
                    $param['data']['student']['pes_id'] = $pessoa->pes_id;
                    $param['data']['student']['firstname'] = $firstName;
                    $param['data']['student']['lastname'] = $lastName;
                    $param['data']['student']['email'] = $pessoa->pes_email;
                    $param['data']['student']['username'] = $pessoa->pes_email;
                    $param['data']['student']['password'] = 'changeme';
                    $param['data']['student']['city'] = $pessoa->pes_cidade;

                    $moodle = new Moodle();
                    $retorno = $moodle->send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }

                    event(new UpdateSincronizacaoEvent($matriculaTurma, $status, $retorno['message']));
                }
            }
        }
    }
}
