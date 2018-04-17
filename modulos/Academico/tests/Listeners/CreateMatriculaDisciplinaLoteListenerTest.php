<?php

use GuzzleHttp\Client;
use Tests\ModulosTestCase;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Harpia\Moodle\Facades\Moodle;
use GuzzleHttp\Handler\MockHandler;
use Modulos\Academico\Events\CreateGrupoEvent;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Academico\Events\CreateMatriculaDisciplinaEvent;
use Modulos\Academico\Events\CreateMatriculaDisciplinaLoteEvent;

/**
 * Class CreateMatriculaDisciplinaLoteListenerTest
 * @group Listeners
 */
class CreateMatriculaDisciplinaLoteListenerTest extends ModulosTestCase
{
    protected $ambiente;
    protected $matriculaCurso;
    protected $matriculaDisciplina;
    protected $sincronizacaoRepository;

    public function setUp()
    {
        parent::setUp();
        $this->sincronizacaoRepository = $this->app->make(\Modulos\Integracao\Repositories\SincronizacaoRepository::class);

        Modulos\Integracao\Models\Servico::truncate();

        $this->createAmbiente();
        $this->createIntegracao();
        $this->createMonitor();
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

        $ambienteServico = factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $this->ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "abcdefgh12345"
        ]);
    }

    private function mockUpDatabaseLote(int $qtdeMatriculas = 20)
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

        $turma = factory(Modulos\Academico\Models\Turma::class)->create($data);

        // Vincular com o ambiente
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $this->ambiente->amb_id
        ]);

        // Mapeia a turma
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);

        // Eventos de turma
        $turmaMapeadaEvent = new TurmaMapeadaEvent($turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);

        // Cria o grupo
        $grupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
            'grp_nome' => "Group A"
        ]);

        // Eventos de grupo
        $createGroupEvent = new CreateGrupoEvent($grupo);

        $sincronizacaoListener->handle($createGroupEvent);

        // Matricula disciplina
        $matriculasDisciplinas = collect([]);

        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_trm_id' => $turma->trm_id
        ]);

        for ($i = 0; $i < $qtdeMatriculas; $i++) {
            // Cria a matricula no curso
            $matriculaCurso = factory(\Modulos\Academico\Models\Matricula::class)->create([
                'mat_trm_id' => $turma->trm_id,
                'mat_grp_id' => $grupo->grp_id,
            ]);

            $matriculasDisciplinas[] = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
                'mof_mat_id' => $matriculaCurso->mat_id,
                'mof_ofd_id' => $ofertaDisciplina->ofd_id
            ]);
        }

        return $matriculasDisciplinas;
    }

    public function testHandleWithSuccess()
    {
        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "id" => random_int(1, 10),
                "status" => "success",
                "message" => "Alunos matriculado com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $createMatriculaDisciplinaLoteListener = $this->app->make(\Modulos\Academico\Listeners\CreateMatriculaDisciplinaLoteListener::class);

        $matriculas = $this->mockUpDatabaseLote(10);

        \Modulos\Integracao\Models\Sincronizacao::truncate(); // Limpa eventos relacionados ao Mock

        $this->assertEquals(0, \Modulos\Integracao\Models\Sincronizacao::all()->count());

        $matriculaDisciplinaLoteEvent = new CreateMatriculaDisciplinaLoteEvent($matriculas);
        $sincronizacaoListener->handle($matriculaDisciplinaLoteEvent);

        $this->expectsEvents(\Modulos\Integracao\Events\UpdateSincronizacaoEvent::class);
        $createMatriculaDisciplinaLoteListener->handle($matriculaDisciplinaLoteEvent);

        $result = $this->sincronizacaoRepository->all();

        $this->assertEquals(10, $result->count());

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

    public function testHandleWithFail()
    {
        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Sem resposta para causar uma excecao
        $mock = new MockHandler([]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $createMatriculaDisciplinaLoteListener = $this->app->make(\Modulos\Academico\Listeners\CreateMatriculaDisciplinaLoteListener::class);

        $matriculas = $this->mockUpDatabaseLote(10);

        \Modulos\Integracao\Models\Sincronizacao::truncate(); // Limpa eventos relacionados ao Mock

        $this->assertEquals(0, \Modulos\Integracao\Models\Sincronizacao::all()->count());

        $matriculaDisciplinaLoteEvent = new CreateMatriculaDisciplinaLoteEvent($matriculas);
        $sincronizacaoListener->handle($matriculaDisciplinaLoteEvent);

        $this->expectsEvents(\Modulos\Integracao\Events\UpdateSincronizacaoEvent::class);
        $createMatriculaDisciplinaLoteListener->handle($matriculaDisciplinaLoteEvent);

        $result = $this->sincronizacaoRepository->all();

        $this->assertEquals(10, $result->count());

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
