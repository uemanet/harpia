<?php
namespace Modulos\Academico\Listeners;

use Harpia\Event\Event;
use Harpia\Moodle\Moodle;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarMatriculaDisciplinaListener
{
    protected $sincronizacaoRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $ambienteVirtualRepository;
    protected $alunoRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository,
        AlunoRepository $alunoRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->alunoRepository = $alunoRepository;
    }

    public function handle(Event $event)
    {
        $matriculasMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_matriculas_ofertas_disciplinas',
            'sym_status' => 1
        ]);

        if ($matriculasMigrar->count()) {
            foreach ($matriculasMigrar as $reg) {
                // busca a matricula na oferta de disciplina
                $matriculaOfertaDisciplina = $this->matriculaOfertaDisciplinaRepository->find($reg->sym_table_id);

                // pega a matricula do aluno no curso
                $matriculaCurso = $matriculaOfertaDisciplina->matriculaCurso;

                // ambiente virtual vinculado à turma do aluno
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($matriculaCurso->mat_trm_id);

                if ($ambiente) {
                    $param = [];

                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_enrol_student_discipline';
                    $param['action'] = 'CREATE';

                    $param['data']['enrol']['mof_id'] = $matriculaOfertaDisciplina->mof_id;

                    // pega as informações do aluno
                    $aluno = $this->alunoRepository->find($matriculaCurso->mat_alu_id);

                    $param['data']['enrol']['pes_id'] = $aluno->alu_pes_id;
                    $param['data']['enrol']['ofd_id'] = $matriculaOfertaDisciplina->mof_ofd_id;

                    $moodleSync = new Moodle();

                    $retorno = $moodleSync->send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }

                    event(new AtualizarSyncEvent($matriculaOfertaDisciplina, $status, $retorno['message']));
                }
            }
        }
    }
}
