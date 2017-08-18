<?php

use Modulos\Integracao\Models\Sincronizacao;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Integracao\Listeners\SincronizacaoListener;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Integracao\Listeners\UpdateSincronizacaoListener;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;

class UpdateSincronizacaoListenerTest extends TestCase
{
    protected $ambiente;
    protected $sincronizacaoRepository;
    protected $turma;

    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__ . '/../../../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->sincronizacaoRepository = new SincronizacaoRepository(new Sincronizacao());

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

    public function testHandle()
    {
        $sincronizacaoListener = new SincronizacaoListener($this->sincronizacaoRepository);

        $turmaMapeadaEvent = new TurmaMapeadaEvent($this->turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);

        $this->seeInDatabase('int_sync_moodle', [
            'sym_table' => $turmaMapeadaEvent->getData()->getTable(),
            'sym_table_id' => $turmaMapeadaEvent->getData()->getKey(),
            'sym_action' => $turmaMapeadaEvent->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $turmaMapeadaEvent->getExtra()
        ]);

        $updateSyncEvent = new UpdateSincronizacaoEvent($turmaMapeadaEvent->getData(), 3, "Testing", $turmaMapeadaEvent->getAction());
        $updateListener = new UpdateSincronizacaoListener($this->sincronizacaoRepository);

        $updateListener->handle($updateSyncEvent);

        $this->assertEquals(1, Sincronizacao::all()->count());

        $this->assertNotNull(Sincronizacao::all()->first()->sym_data_envio);

        $this->seeInDatabase('int_sync_moodle', [
            'sym_table' => $turmaMapeadaEvent->getData()->getTable(),
            'sym_table_id' => $turmaMapeadaEvent->getData()->getKey(),
            'sym_action' => $turmaMapeadaEvent->getAction(),
            'sym_status' => 3,
            'sym_mensagem' => "Testing",
            'sym_extra' => $turmaMapeadaEvent->getExtra()
        ]);
    }
}
