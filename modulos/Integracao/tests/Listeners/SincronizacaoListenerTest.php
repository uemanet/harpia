<?php

use Tests\ModulosTestCase;
use Illuminate\Support\Facades\Schema;
use Modulos\Academico\Events\CreateGrupoEvent;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Academico\Events\CreateMatriculaTurmaEvent;
use Modulos\Academico\Events\CreateMatriculaDisciplinaEvent;
use Modulos\Academico\Events\CreateMatriculaDisciplinaLoteEvent;

/**
 * Class SincronizacaoListenerTest
 * @group Listeners
 */
class SincronizacaoListenerTest extends ModulosTestCase
{
    protected $turma;
    protected $ambiente;
    protected $sincronizacaoRepository;

    public function setUp(): void
    {
        parent::setUp();
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

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $this->ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
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

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $this->ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "abcdefgh12345"
        ]);
    }


    /**
     * Fazer mock-up de todas as etapas necessarias para se mapear uma turma em um ambiente virtual
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
    }

    private function mockUpDatabaseLote(int $qtdeMatriculas = 20)
    {
        // Cria a turma
        $data = [
            'trm_id' => random_int(50, 1000),
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

        // Mapeia a turma
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $turmaMapeadaListener = $this->app->make(\Modulos\Integracao\Listeners\TurmaMapeadaListener::class);
        $createGroupListener = $this->app->make(\Modulos\Academico\Listeners\CreateGrupoListener::class);
        $createMatriculaTurmaListener = $this->app->make(\Modulos\Academico\Listeners\CreateMatriculaTurmaListener::class);

        // Eventos de turma
        $turmaMapeadaEvent = new TurmaMapeadaEvent($this->turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);
        $turmaMapeadaListener->handle($turmaMapeadaEvent);

        // Cria o grupo
        $grupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $this->turma->trm_id,
            'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
            'grp_nome' => "Group A"
        ]);

        // Eventos de grupo
        $createGroupEvent = new CreateGrupoEvent($grupo);

        $sincronizacaoListener->handle($createGroupEvent);
        $createGroupListener->handle($createGroupEvent);

        // Cria a matricula no curso
        $matriculaCurso = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_trm_id' => $this->turma->trm_id,
            'mat_grp_id' => $grupo->grp_id,
        ]);

        $createMatriculaTurmaEvent = new CreateMatriculaTurmaEvent($matriculaCurso);

        // Eventos de Matricula no curso
        $sincronizacaoListener->handle($createMatriculaTurmaEvent);
        $createMatriculaTurmaListener->handle($createMatriculaTurmaEvent);

        // Matricula disciplina
        $matriculasDisciplinas = collect([]);

        for ($i = 0; $i < $qtdeMatriculas; $i++) {
            $matriculasDisciplinas[] = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
                'mof_mat_id' => $matriculaCurso->mat_id,
            ]);
        }

        return $matriculasDisciplinas;
    }

    public function testHandle()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);

        $turmaMapeadaEvent = new TurmaMapeadaEvent($this->turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);

        $this->assertDatabaseHas('int_sync_moodle', [
            'sym_table' => $turmaMapeadaEvent->getData()->getTable(),
            'sym_table_id' => $turmaMapeadaEvent->getData()->getKey(),
            'sym_action' => $turmaMapeadaEvent->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $turmaMapeadaEvent->getExtra()
        ]);
    }

    public function testHandleMigracaoLote()
    {
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);

        $matriculas = $this->mockUpDatabaseLote(10);

        \Modulos\Integracao\Models\Sincronizacao::truncate(); // Limpa eventos relacionados ao Mock

        $this->assertEquals(0, \Modulos\Integracao\Models\Sincronizacao::all()->count());

        $matriculaDisciplinaLoteEvent = new CreateMatriculaDisciplinaLoteEvent($matriculas);
        $sincronizacaoListener->handle($matriculaDisciplinaLoteEvent);

        $this->assertEquals(10, \Modulos\Integracao\Models\Sincronizacao::all()->count());

        // Cria um evento de matricula com alguma das matriculas passadas.
        // O objetivo eh checar se ha um log individual daquele registro na tabela de sincronizacao
        $matriculaDisciplinaEvent = new CreateMatriculaDisciplinaEvent($matriculaDisciplinaLoteEvent->getItems()->random());

        $this->assertDatabaseHas('int_sync_moodle', [
            'sym_table' => $matriculaDisciplinaEvent->getData()->getTable(),
            'sym_table_id' => $matriculaDisciplinaEvent->getData()->getKey(),
            'sym_action' => $matriculaDisciplinaEvent->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $matriculaDisciplinaEvent->getExtra()
        ]);
    }
}
