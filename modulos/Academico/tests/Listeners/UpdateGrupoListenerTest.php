<?php

use Modulos\Academico\Models\Grupo;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Vinculo;
use Modulos\Academico\Models\PeriodoLetivo;
use Modulos\Integracao\Models\Sincronizacao;
use Modulos\Academico\Events\UpdateGrupoEvent;
use Modulos\Integracao\Models\AmbienteVirtual;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Listeners\UpdateGrupoListener;
use Modulos\Academico\Repositories\VinculoRepository;
use Modulos\Integracao\Listeners\TurmaMapeadaListener;
use Modulos\Integracao\Listeners\SincronizacaoListener;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class UpdateGrupoListenerTest extends TestCase
{
    protected $ambiente;
    protected $sincronizacaoRepository;
    protected $turma;
    protected $grupo;

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

        // Mapeia a turma
        $sincronizacaoListener = new SincronizacaoListener($this->sincronizacaoRepository);

        $periodoLetivoRepository = new PeriodoLetivoRepository(new PeriodoLetivo());
        $vinculoRepository = new VinculoRepository(new Vinculo());
        $cursoRepository = new CursoRepository(new Curso(), $vinculoRepository);
        $turmaRepository = new TurmaRepository(new Turma(), $cursoRepository, $periodoLetivoRepository);
        $ambienteVirtualRepository = new AmbienteVirtualRepository(new AmbienteVirtual());

        $turmaMapeadaListener = new TurmaMapeadaListener(
            $turmaRepository,
            $cursoRepository,
            $periodoLetivoRepository,
            $ambienteVirtualRepository,
            $this->sincronizacaoRepository
        );

        $turmaMapeadaEvent = new TurmaMapeadaEvent($this->turma);

        $sincronizacaoListener->handle($turmaMapeadaEvent);
        $turmaMapeadaListener->handle($turmaMapeadaEvent);

        // Cria o grupo
        $this->grupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $this->turma->trm_id,
            'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
            'grp_nome' => "Group A"
        ]);
    }

    public function testHandle()
    {
        $sincronizacaoListener = new SincronizacaoListener($this->sincronizacaoRepository);
        $grupoRepository = new GrupoRepository(new Grupo());
        $ambienteVirtualRepository = new AmbienteVirtualRepository(new AmbienteVirtual());
        $updateGrupoListener = new UpdateGrupoListener($grupoRepository, $ambienteVirtualRepository);

        $this->assertEquals(1, Sincronizacao::all()->count());

        $updateGrupoEvent = new UpdateGrupoEvent($this->grupo);
        $sincronizacaoListener->handle($updateGrupoEvent);

        $this->seeInDatabase('int_sync_moodle', [
            'sym_table' => $updateGrupoEvent->getData()->getTable(),
            'sym_table_id' => $updateGrupoEvent->getData()->getKey(),
            'sym_action' => $updateGrupoEvent->getAction(),
            'sym_status' => 1,
            'sym_mensagem' => null,
            'sym_data_envio' => null,
            'sym_extra' => $updateGrupoEvent->getExtra()
        ]);

        $grupoRepository->update(["grp_nome" => "Grupo B"], $this->grupo->grp_id);

        $this->seeInDatabase('acd_grupos', [
            "grp_id" => $this->grupo->grp_id,
            "grp_trm_id" => $this->turma->trm_id,
            "grp_pol_id" => $this->grupo->grp_pol_id,
            "grp_nome" => "Grupo B",
        ]);

        $this->expectsEvents(\Modulos\Integracao\Events\UpdateSincronizacaoEvent::class);
        $updateGrupoListener->handle($updateGrupoEvent);

        $this->assertEquals(2, Sincronizacao::all()->count());
    }
}
