<?php

use Harpia\Event\SincronizacaoFactory;
use Modulos\Academico\Events\CreateGrupoEvent;
use Modulos\Academico\Events\CreateOfertaDisciplinaEvent;
use Modulos\Academico\Events\CreateVinculoTutorEvent;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Academico\Events\CreateMatriculaTurmaEvent;
use Modulos\Academico\Events\CreateMatriculaDisciplinaEvent;

class SincronizacaoFactoryTest extends TestCase
{
    protected $tutor;
    protected $turma;
    protected $grupo;
    protected $ambiente;
    protected $tutorGrupo;
    protected $matriculaCurso;
    protected $ofertaDisciplina;
    protected $matriculaDisciplina;
    protected $sincronizacaoRepository;

    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->sincronizacaoRepository = $this->app->make(\Modulos\Integracao\Repositories\SincronizacaoRepository::class);

        Modulos\Integracao\Models\Servico::truncate();

        $this->createAmbiente();
        $this->createIntegracao();
        $this->createMonitor();
        $this->mockUpDatabase();
    }

    /**
     * Cria um ambiente de testes a partir das variaveis de ambiente
     *
     * @return void
     */
    private function createAmbiente()
    {
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $this->ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);
    }

    /**
     * Cria o registro do plugin de integracao a partir das variaveis de ambiente
     *
     * @return void
     */
    private function createIntegracao()
    {
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        $ambienteServico = factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $this->ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => env("MOODLE_INTEGRACAO_TEST_TOKEN")
        ]);
    }

    /**
     * Cria o registro do plugin de monitoramento a partir das variaveis de ambiente
     *
     * @return void
     */
    private function createMonitor()
    {
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_nome' => "Monitor",
            'ser_slug' => "get_tutor_online_time"
        ]);

        $ambienteServico = factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $this->ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "abcdefgh12345"
        ]);
    }


    /**
     * Fazer mock-up de todas as etapas necessarias para se executar o teste
     *
     * @return void
     */
    private function mockUpDatabase()
    {
        // Cria a turma
        $data = [
            'trm_id' => random_int(50, 100),
            'trm_ofc_id' => factory(Modulos\Academico\Models\OfertaCurso::class)->create()->ofc_id,
            'trm_per_id' => factory(Modulos\Academico\Models\PeriodoLetivo::class)->create()->per_id,
            'trm_nome' => "Turma de Teste",
            'trm_integrada' => 1,
            'trm_qtd_vagas' => 50
        ];

        $this->turma = factory(Modulos\Academico\Models\Turma::class)->create($data);

        // Vincular com o ambiente
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $this->turma->trm_id,
            'atr_amb_id' => $this->ambiente->amb_id
        ]);

        // Oferta de disciplina
        $ofertaDisciplinaData =  [
            'ofd_trm_id' => $this->turma->trm_id,
            'ofd_per_id' => $this->turma->trm_per_id,
        ];

        $this->ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create($ofertaDisciplinaData);

        // Listeners
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $turmaMapeadaListener = $this->app->make(\Modulos\Integracao\Listeners\TurmaMapeadaListener::class);
        $createGroupListener = $this->app->make(\Modulos\Academico\Listeners\CreateGrupoListener::class);
        $createMatriculaTurmaListener = $this->app->make(\Modulos\Academico\Listeners\CreateMatriculaTurmaListener::class);
        $createMatriculaDisciplinaListener = $this->app->make(\Modulos\Academico\Listeners\CreateMatriculaDisciplinaListener::class);
        $createOfertaDisciplinaListener = $this->app->make(\Modulos\Academico\Listeners\CreateOfertaDisciplinaListener::class);
        $createVinculoListener = $this->app->make(\Modulos\Academico\Listeners\CreateVinculoTutorListener::class);

        // Eventos de turma
        $turmaMapeadaEvent = new TurmaMapeadaEvent($this->turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);
        $turmaMapeadaListener->handle($turmaMapeadaEvent);

        // Cria o grupo
        $this->grupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $this->turma->trm_id,
            'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
            'grp_nome' => "Group A"
        ]);

        // Eventos de grupo
        $createGroupEvent = new CreateGrupoEvent($this->grupo);

        $sincronizacaoListener->handle($createGroupEvent);
        $createGroupListener->handle($createGroupEvent);

        // Criar o tutor
        $this->tutor = factory(\Modulos\Academico\Models\Tutor::class)->create();

        // Vinculo
        $this->tutorGrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create([
            'ttg_tut_id' => $this->tutor->tut_id,
            'ttg_grp_id' => $this->grupo->grp_id,
        ]);

        // Cria a matricula no curso
        $this->matriculaCurso = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_trm_id' => $this->turma->trm_id,
            'mat_grp_id' => $this->grupo->grp_id,
        ]);

        $createMatriculaTurmaEvent = new CreateMatriculaTurmaEvent($this->matriculaCurso);

        // Eventos de Matricula no curso
        $sincronizacaoListener->handle($createMatriculaTurmaEvent);
        $createMatriculaTurmaListener->handle($createMatriculaTurmaEvent);

        // Matricula disciplina
        $this->matriculaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $this->matriculaCurso->mat_id,
        ]);

        // Eventos de Matricula em disciplina
        $createMatriculaDisciplinaEvent = new CreateMatriculaDisciplinaEvent($this->matriculaDisciplina);
        $sincronizacaoListener->handle($createMatriculaDisciplinaEvent);
        $createMatriculaDisciplinaListener->handle($createMatriculaDisciplinaEvent);

        // Eventos de oferta disciplina
        $createOfertaDisciplinaEvent = new CreateOfertaDisciplinaEvent($this->ofertaDisciplina);
        $sincronizacaoListener->handle($createOfertaDisciplinaEvent);
        $createOfertaDisciplinaListener->handle($createOfertaDisciplinaEvent);

        // Eventos de vincular tutor ao grupo
        $createVinculoEvent = new CreateVinculoTutorEvent($this->tutorGrupo);

        $sincronizacaoListener->handle($createVinculoEvent);
        $createVinculoListener->handle($createVinculoEvent);
    }

    public function testFactoryTurmaMapeadaEvent()
    {
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_turmas')
            ->where('sym_action', '=', 'CREATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Integracao\Events\TurmaMapeadaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryUpdateTurmaEvent()
    {
        // Cria evento de atualizacao de turma
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $updateTurmaListener = $this->app->make(\Modulos\Academico\Listeners\UpdateTurmaListener::class);

        $turmaRepository = $this->app->make(\Modulos\Academico\Repositories\TurmaRepository::class);

        // Atualiza a turma
        $turmaRepository->update(["trm_nome" => "Teste Mudança de Nome"], $this->turma->trm_id);

        $updateTurmaEvent = new \Modulos\Academico\Events\UpdateTurmaEvent($this->turma);
        $sincronizacaoListener->handle($updateTurmaEvent);
        $updateTurmaListener->handle($updateTurmaEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_turmas')
            ->where('sym_action', '=', 'UPDATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(\Modulos\Academico\Events\UpdateTurmaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryTurmaRemovidaEvent()
    {
        // Cria evento de remocao de turma
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $turmaRemovidaListener = $this->app->make(\Modulos\Integracao\Listeners\TurmaRemovidaListener::class);

        $turmaRemovidaEvent = new \Modulos\Integracao\Events\TurmaRemovidaEvent($this->turma, $this->ambiente->amb_id);

        $sincronizacaoListener->handle($turmaRemovidaEvent);
        $turmaRemovidaListener->handle($turmaRemovidaEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_turmas')
            ->where('sym_action', '=', 'DELETE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(\Modulos\Integracao\Events\TurmaRemovidaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryCreateGrupoEvent()
    {
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_grupos')
            ->where('sym_action', '=', 'CREATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\CreateGrupoEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryUpdateGrupoEvent()
    {
        // Cria os eventos de atualizacao de grupo
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $updateGrupoListener = $this->app->make(\Modulos\Academico\Listeners\UpdateGrupoListener::class);
        $grupoRepository = $this->app->make(\Modulos\Academico\Repositories\GrupoRepository::class);

        // Atualiza o grupo
        $grupoRepository->update(["grp_nome" => "Grupo B"], $this->grupo->grp_id);

        $updateGrupoEvent = new \Modulos\Academico\Events\UpdateGrupoEvent($this->grupo);

        $sincronizacaoListener->handle($updateGrupoEvent);
        $updateGrupoListener->handle($updateGrupoEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_grupos')
            ->where('sym_action', '=', 'UPDATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\UpdateGrupoEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryDeleteGrupoEvent()
    {
        // Cria os eventos de exclusao de grupo
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $deleteGroupListener = $this->app->make(\Modulos\Academico\Listeners\DeleteGrupoListener::class);

        $deleteGroupEvent = new \Modulos\Academico\Events\DeleteGrupoEvent($this->grupo, $this->ambiente->amb_id);

        $sincronizacaoListener->handle($deleteGroupEvent);
        $deleteGroupListener->handle($deleteGroupEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_grupos')
            ->where('sym_action', '=', 'DELETE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\DeleteGrupoEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryCreateOfertaDisciplinaEvent()
    {
        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_ofertas_disciplinas')
            ->where('sym_action', '=', 'CREATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\CreateOfertaDisciplinaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryUpdateProfessorOfertaDisciplinaEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $updateProfessorDisciplinaListener = $this->app->make(\Modulos\Academico\Listeners\UpdateProfessorDisciplinaListener::class);

        // Atualiza o professor da disciplina
        $novoProfessor = factory(\Modulos\Academico\Models\Professor::class)->create();
        $ofertaDisciplinaRepository = $this->app->make(\Modulos\Academico\Repositories\OfertaDisciplinaRepository::class);

        $ofertaDisciplinaRepository->update([
            'ofd_prf_id' => $novoProfessor->prf_id
        ], $this->ofertaDisciplina->ofd_id);

        // Evento de atualizacao de professor
        $updateProfessorDisciplinaEvent = new \Modulos\Academico\Events\UpdateProfessorDisciplinaEvent($this->ofertaDisciplina);
        $sincronizacaoListener->handle($updateProfessorDisciplinaEvent);
        $updateProfessorDisciplinaListener->handle($updateProfessorDisciplinaEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_ofertas_disciplinas')
            ->where('sym_action', '=', 'UPDATE_PROFESSOR_OFERTA_DISCIPLINA')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\UpdateProfessorDisciplinaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryDeleteOfertaDisciplinaEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $deleteOfertaDisciplinaListener = $this->app->make(\Modulos\Academico\Listeners\DeleteOfertaDisciplinaListener::class);

        // Eventos de exclusao de disciplina
        $deleteOfertaDisciplinaEvent = new \Modulos\Academico\Events\DeleteOfertaDisciplinaEvent($this->ofertaDisciplina, $this->ambiente->amb_id);

        $sincronizacaoListener->handle($deleteOfertaDisciplinaEvent);
        $deleteOfertaDisciplinaListener->handle($deleteOfertaDisciplinaEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_ofertas_disciplinas')
            ->where('sym_action', '=', 'DELETE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\DeleteOfertaDisciplinaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryCreateMatriculaOfertaDisciplinaEvent()
    {
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_matriculas_ofertas_disciplinas')
            ->where('sym_action', '=', 'CREATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\CreateMatriculaDisciplinaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryUpdatePessoaEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $updatePessoaListener = $this->app->make(\Modulos\Geral\Listeners\UpdatePessoaListener::class);

        // Eventos de atualizacao de pessoa
        $pessoa = \Modulos\Geral\Models\Pessoa::all()->first();

        $updatePessoaEvent = new \Modulos\Geral\Events\UpdatePessoaEvent($pessoa, $this->ambiente->amb_id);

        $sincronizacaoListener->handle($updatePessoaEvent);
        $updatePessoaListener->handle($updatePessoaEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'gra_pessoas')
            ->where('sym_action', '=', 'UPDATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Geral\Events\UpdatePessoaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryCreateMatriculaTurmaEvent()
    {
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_matriculas')
            ->where('sym_action', '=', 'CREATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\CreateMatriculaTurmaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryUpdateSituacaoMatriculaEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $updateSituacaoMatriculaListener = $this->app->make(\Modulos\Academico\Listeners\UpdateSituacaoMatriculaListener::class);
        $matriculaRepository = $this->app->make(\Modulos\Academico\Repositories\MatriculaCursoRepository::class);

        // Atualiza a situacao da matricula
        $matriculaRepository->update(['mat_situacao' => 'trancado'], $this->matriculaCurso->mat_id);

        $updateSituacaoMatriculaEvent = new \Modulos\Academico\Events\UpdateSituacaoMatriculaEvent($this->matriculaCurso);
        $sincronizacaoListener->handle($updateSituacaoMatriculaEvent);
        $updateSituacaoMatriculaListener->handle($updateSituacaoMatriculaEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_matriculas')
            ->where('sym_action', '=', 'UPDATE_SITUACAO_MATRICULA')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\UpdateSituacaoMatriculaEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryUpdateGrupoAlunoEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $updateGrupoAlunoListener = $this->app->make(\Modulos\Academico\Listeners\UpdateGrupoAlunoListener::class);
        $matriculaCursoRepository = $this->app->make(\Modulos\Academico\Repositories\MatriculaCursoRepository::class);

        // Cria novo grupo
        $novoGrupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $this->turma->trm_id,
            'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
            'grp_nome' => "Group B"
        ]);

        // Atualiza a matricula para novo grupo
        $oldGrupo = $this->matriculaCurso->mat_grp_id;

        $matriculaCursoRepository->update([
            'mat_grp_id' => $novoGrupo->grp_id
        ], $this->matriculaCurso->mat_id);

        // Eventos de atualizacao de grupo
        $updateGrupoAlunoEvent = new \Modulos\Academico\Events\UpdateGrupoAlunoEvent($this->matriculaCurso, $oldGrupo);

        $sincronizacaoListener->handle($updateGrupoAlunoEvent);
        $updateGrupoAlunoListener->handle($updateGrupoAlunoEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_matriculas')
            ->where('sym_action', '=', 'UPDATE_GRUPO_ALUNO')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\UpdateGrupoAlunoEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryDeleteGrupoAlunoEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $deleteGrupoAlunoListener = $this->app->make(\Modulos\Academico\Listeners\DeleteGrupoAlunoListener::class);

        $oldGrupo = $this->matriculaCurso->mat_grp_id;

        // Dispara evento e remocao de grupo
        $deleteGrupoAlunoEvent = new \Modulos\Academico\Events\DeleteGrupoAlunoEvent($this->matriculaCurso, $oldGrupo);

        $sincronizacaoListener->handle($deleteGrupoAlunoEvent);
        $deleteGrupoAlunoListener->handle($deleteGrupoAlunoEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_matriculas')
            ->where('sym_action', '=', 'DELETE_GRUPO_ALUNO')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\DeleteGrupoAlunoEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryCreateVinculoTutorEvent()
    {
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_tutores_grupos')
            ->where('sym_action', '=', 'CREATE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\CreateVinculoTutorEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");
    }

    public function testFactoryDeleteVinculoTutorEvent()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $deleteVinculoListener = $this->app->make(\Modulos\Academico\Listeners\DeleteVinculoTutorListener::class);

        // Evento de exclusao de vinculo de tutor
        $deleteVinculoEvent = new \Modulos\Academico\Events\DeleteVinculoTutorEvent($this->tutorGrupo);

        $sincronizacaoListener->handle($deleteVinculoEvent);
        $deleteVinculoListener->handle($deleteVinculoEvent);

        // Verifica o factory
        $sincronizacao = \Modulos\Integracao\Models\Sincronizacao::where('sym_table', '=', 'acd_tutores_grupos')
            ->where('sym_action', '=', 'DELETE')
            ->get()
            ->first();

        $event = SincronizacaoFactory::factory($sincronizacao);

        $this->assertInstanceOf(Modulos\Academico\Events\DeleteVinculoTutorEvent::class, $event);
        $this->assertFalse($event->isFirstAttempt(), "Evento não corresponde a primeira tentativa");

    }

    public function testFactoryThrowException()
    {
        $date = new \DateTime();

        $sincronizacao = factory(\Modulos\Integracao\Models\Sincronizacao::class)->create([
            'sym_table' => "any_tables",
            'sym_table_id' => 10,
            'sym_action' => "ANY_ACTION",
            'sym_status' => 3,
            'sym_mensagem' => "Message",
            'sym_data_envio' => $date->format("Y-m-d H:i:s"),
            'sym_extra' => "any"
        ]);

        $this->expectException(\Exception::class);
        $event = SincronizacaoFactory::factory($sincronizacao);
    }
}
