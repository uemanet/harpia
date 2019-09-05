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
use Modulos\Academico\Events\CreateVinculoTutorEvent;

/**
 * Class CreateVinculoTutorListenerTest
 * @group Listeners
 */
class CreateVinculoTutorListenerTest extends ModulosTestCase
{
    protected $ambiente;
    protected $sincronizacaoRepository;
    protected $turma;
    protected $grupo;
    protected $tutor;
    protected $tutorGrupo;

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

        // Mapeia a turma
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $turmaMapeadaListener = $this->app->make(\Modulos\Integracao\Listeners\TurmaMapeadaListener::class);

        $turmaMapeadaEvent = new TurmaMapeadaEvent($this->turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);
        $turmaMapeadaListener->handle($turmaMapeadaEvent);

        // Cria o grupo
        $this->grupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $this->turma->trm_id,
            'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
            'grp_nome' => "Group A"
        ]);

        // Criar o tutor
        $this->tutor = factory(\Modulos\Academico\Models\Tutor::class)->create();

        // Vinculo
        $this->tutorGrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create([
            'ttg_tut_id' => $this->tutor->tut_id,
            'ttg_grp_id' => $this->grupo->grp_id,
        ]);
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
                "message" => "Vinculo criado com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $createVinculoListener = $this->app->make(\Modulos\Academico\Listeners\CreateVinculoTutorListener::class);

        $this->assertEquals(1, $this->sincronizacaoRepository->count());

        $createVinculoEvent = new CreateVinculoTutorEvent($this->tutorGrupo);
        $sincronizacaoListener->handle($createVinculoEvent);

        $this->assertDatabaseHas('int_sync_moodle', [
            'sym_table' => $createVinculoEvent->getData()->getTable(),
            'sym_table_id' => $createVinculoEvent->getData()->getKey(),
            'sym_action' => $createVinculoEvent->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $createVinculoEvent->getExtra()
        ]);

        $this->expectsEvents(\Modulos\Integracao\Events\UpdateSincronizacaoEvent::class);
        $createVinculoListener->handle($createVinculoEvent);

        $this->assertEquals(2, $this->sincronizacaoRepository->count());
    }

    public function testHandleWithFail()
    {
        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // A falta do Mock de response causa o disparo de uma excecao no Listener
        $handler = HandlerStack::create();
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $createVinculoListener = $this->app->make(\Modulos\Academico\Listeners\CreateVinculoTutorListener::class);

        $this->assertEquals(1, $this->sincronizacaoRepository->count());

        $createVinculoEvent = new CreateVinculoTutorEvent($this->tutorGrupo);
        $sincronizacaoListener->handle($createVinculoEvent);

        $this->assertDatabaseHas('int_sync_moodle', [
            'sym_table' => $createVinculoEvent->getData()->getTable(),
            'sym_table_id' => $createVinculoEvent->getData()->getKey(),
            'sym_action' => $createVinculoEvent->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $createVinculoEvent->getExtra()
        ]);

        $this->expectsEvents(\Modulos\Integracao\Events\UpdateSincronizacaoEvent::class);
        $createVinculoListener->handle($createVinculoEvent);

        $this->assertEquals(2, $this->sincronizacaoRepository->count());
    }
}
