<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Pessoa;
use Modulos\Academico\Models\Aluno;
use Modulos\Geral\Models\Documento;
use Modulos\Academico\Models\Matricula;
use Modulos\Integracao\Models\Sincronizacao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Events\TurmaMapeadaEvent;
use Modulos\Geral\Repositories\PessoaRepository;

class PessoaRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(PessoaRepository::class);
        $this->table = 'gra_pessoas';
    }

    public function testCreate()
    {
        $data = factory(Pessoa::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Pessoa::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->getOriginal());
    }

    public function testFind()
    {
        $entry = factory(Pessoa::class)->create();

        $id = $entry->pes_id;
        $fromRepository = $this->repo->find($id);

        // Accessors
        $data['pes_estrangeiro'] = $entry->getOriginal('pes_estrangeiro');
        $data['pes_nascimento'] = $entry->getOriginal('pes_nascimento');

        $fromRepositoryArray = $fromRepository->getOriginal();

        $fromRepositoryArray['pes_estrangeiro'] = (bool)$fromRepository->getOriginal('pes_estrangeiro');
        $fromRepositoryArray['pes_nascimento'] = $fromRepository->getOriginal('pes_nascimento');

        $this->assertInstanceOf(Pessoa::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->getOriginal());
        $this->assertEquals($entry->getOriginal(), $fromRepositoryArray);
    }

    public function testUpdate()
    {
        $entry = factory(Pessoa::class)->create();
        $id = $entry->pes_id;

        $data = $entry->toArray();

        $data['pes_bairro'] = "bairro novo";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        // Accessor
        $data['pes_estrangeiro'] = $entry->getOriginal('pes_estrangeiro');
        $data['pes_nascimento'] = $entry->getOriginal('pes_nascimento');

        $fromRepositoryArray = $fromRepository->getOriginal();

        $fromRepositoryArray['pes_id'] = $fromRepository->getOriginal('pes_id');
        $fromRepositoryArray['pes_estrangeiro'] = (bool)$fromRepository->getOriginal('pes_estrangeiro');
        $fromRepositoryArray['pes_nascimento'] = $fromRepository->getOriginal('pes_nascimento');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Pessoa::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testDelete()
    {
        $entry = factory(Pessoa::class)->create();
        $id = $entry->pes_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Pessoa::class, 2)->create();

        $model = new Pessoa();
        $expected = $model->pluck('sym_table', 'pes_id');
        $fromRepository = $this->repo->lists('pes_id', 'sym_table');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Pessoa::class, 2)->create();

        factory(Pessoa::class)->create([
            'pes_nacionalidade' => 'Canadense'
        ]);

        $searchResult = $this->repo->search(array(['pes_nacionalidade', '=', 'Canadense']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Pessoa::class, 2)->create();

        $entry = factory(Pessoa::class)->create([
            'pes_nome' => "fulano de tal"
        ]);

        $expected = [
            'pes_id' => $entry->pes_id,
            'pes_nome' => $entry->pes_nome
        ];

        $searchResult = $this->repo->search(array(['pes_nome', '=', "fulano de tal"]), ['pes_id', 'pes_nome']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
        $this->assertEquals($expected, $searchResult->first()->toArray());
    }

    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(Pessoa::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Pessoa::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Pessoa();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Pessoa::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Pessoa::class, 2)->create();

        $sort = [
            'field' => 'pes_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->pes_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Pessoa::class, 2)->create();
        factory(Pessoa::class)->create([
            'pes_nome' => 'fulano de tal',
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => '=',
                'term' => 'fulano de tal'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('fulano de tal', $response->first()->pes_nome);
    }

    public function testPaginateRequest()
    {
        factory(Pessoa::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'pes_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyEmail()
    {
        factory(Pessoa::class, 2)->create();

        $this->assertFalse($this->repo->verifyEmail('findthisemail@email.com'));

        factory(Pessoa::class)->create([
            'pes_email' => 'emailtofind@email.com'
        ]);

        $this->assertTrue($this->repo->verifyEmail('emailtofind@email.com'));
    }

    public function testFindPessoaByCpf()
    {
        factory(Pessoa::class, 2)->create();

        $result = $this->repo->findPessoaByCpf('12345678901');

        $this->assertEquals(null, $result);

        $pessoa = factory(Pessoa::class)->create();

        factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '12345678901'
        ]);

        $result = $this->repo->findPessoaByCpf('12345678901');
        $this->assertInstanceOf(Pessoa::class, $result);
    }

    public function testFindPessoaById()
    {
        factory(Pessoa::class, 2)->create();


        $result = $this->repo->findById(random_int(10, 100));
        $this->assertEquals(null, $result);

        $pessoa = factory(Pessoa::class)->create();
        $id = $pessoa->pes_id;

        factory(Documento::class)->create([
            'doc_pes_id' => $id,
            'doc_conteudo' => '12345678901'
        ]);

        $result = $this->repo->findById($id);
        $this->assertInstanceOf(Pessoa::class, $result);
        $this->assertEquals('12345678901', $result->doc_conteudo);
    }

    public function testUpdatePessoaAmbientes()
    {
        // Cria ambiente
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracoes
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        $ambienteServico = factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Mock de turma
        factory(\Modulos\Geral\Models\Pessoa::class, 10);

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
            'atr_amb_id' => $ambiente->amb_id
        ]);

        // Mapeia a turma
        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);
        $turmaMapeadaEvent = new TurmaMapeadaEvent($turma);
        $sincronizacaoListener->handle($turmaMapeadaEvent);

        $sincronizacaoListener = $this->app->make(\Modulos\Integracao\Listeners\SincronizacaoListener::class);

        $this->assertEquals(1, Sincronizacao::all()->count());

        $pessoa = Pessoa::all()->first();
        $aluno = factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        factory(Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
        ]);

        // Verifica se p metodo esta disparando o evento de atualizacao de pessoa
        $this->expectsEvents(\Modulos\Geral\Events\UpdatePessoaEvent::class);
        $this->repo->updatePessoaAmbientes($pessoa);
    }
}
