<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Models\Titulacao;
use Modulos\Geral\Models\TitulacaoInformacao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\TitulacaoInformacaoRepository;

class TitulacaoInformacaoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(TitulacaoInformacaoRepository::class);
        $this->table = 'gra_titulacoes_informacoes';
    }

    public function testCreate()
    {
        $data = factory(TitulacaoInformacao::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(TitulacaoInformacao::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->getOriginal());
    }

    public function testFind()
    {
        $entry = factory(TitulacaoInformacao::class)->create();

        $id = $entry->tin_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(TitulacaoInformacao::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(TitulacaoInformacao::class)->create();
        $id = $entry->tin_id;

        $data = $entry->toArray();
        $data['tin_titulo'] = "especialista";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(TitulacaoInformacao::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(TitulacaoInformacao::class)->create();
        $id = $entry->tin_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(TitulacaoInformacao::class, 2)->create();

        $model = new TitulacaoInformacao();
        $expected = $model->pluck('tin_titulo', 'tin_id');
        $fromRepository = $this->repo->lists('tin_id', 'tin_titulo');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(TitulacaoInformacao::class, 2)->create();

        factory(TitulacaoInformacao::class)->create([
            'tin_titulo' => 'doutorado'
        ]);

        $searchResult = $this->repo->search(array(['tin_titulo', '=', 'doutorado']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $entry = factory(TitulacaoInformacao::class)->create([
            'tin_titulo' => "bachelor"
        ]);

        $expected = [
            'tin_id' => $entry->tin_id,
            'tin_titulo' => $entry->tin_titulo
        ];

        $searchResult = $this->repo->search(array(['tin_titulo', '=', "bachelor"]), ['tin_id', 'tin_titulo']);

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
        $created = factory(TitulacaoInformacao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(TitulacaoInformacao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new TitulacaoInformacao();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $sort = [
            'field' => 'tin_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->tin_id);
    }

    public function testPaginateWithSearch()
    {
        factory(TitulacaoInformacao::class, 2)->create();
        factory(TitulacaoInformacao::class)->create([
            'tin_titulo' => 'bachelor',
        ]);

        $search = [
            [
                'field' => 'tin_titulo',
                'type' => '=',
                'term' => 'bachelor'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('bachelor', $response->first()->tin_titulo);
    }

    public function testPaginateRequest()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'tin_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testFindBy()
    {
        factory(Pessoa::class, 10)->create();

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
            'pes_id' => 1
        ]);

        $this->assertEquals(0, $result->count());

        $pessoa = factory(Pessoa::class)->create();
        $id = $pessoa->pes_id;

        $titulacao = factory(Titulacao::class)->create([
            'tit_nome' => 'Doutorado'
        ]);

        factory(TitulacaoInformacao::class)->create([
            'tin_pes_id' => $pessoa->pes_id,
            'tin_tit_id' => $titulacao->tit_id,
        ]);

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
            'pes_id' => $id
        ]);

        $this->assertEquals(1, $result->count());
        $this->assertInstanceOf(TitulacaoInformacao::class, $result->first());
    }

    public function testFindByWithSelect()
    {
        factory(Pessoa::class, 10)->create();

        $pessoa = factory(Pessoa::class)->create();
        $id = $pessoa->pes_id;

        $titulacao = factory(Titulacao::class)->create([
            'tit_nome' => 'Doutorado'
        ]);

        factory(TitulacaoInformacao::class)->create([
            'tin_pes_id' => $pessoa->pes_id,
            'tin_tit_id' => $titulacao->tit_id,
        ]);

        $expected = [
            'pes_id' => $pessoa->pes_id,
            'pes_nome' => $pessoa->pes_nome,
            'tit_nome' => $titulacao->tit_nome
        ];

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
            'pes_id' => $id
        ], ['pes_id', 'pes_nome', 'tit_nome']);

        $this->assertEquals(1, $result->count());
        $this->assertInstanceOf(TitulacaoInformacao::class, $result->first());
        $this->assertEquals($expected, $result->first()->toArray());
    }

    public function testFindByWithOrder()
    {
        factory(Pessoa::class, 10)->create();

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
            'pes_id' => 1
        ]);

        $this->assertEquals(0, $result->count());

        $pessoa = factory(Pessoa::class)->create();
        $id = $pessoa->pes_id;

        $sndPessoa = factory(Pessoa::class)->create();
        $sndId = $sndPessoa->pes_id;

        $titulacao = factory(Titulacao::class)->create([
            'tit_nome' => 'Doutorado'
        ]);

        factory(TitulacaoInformacao::class)->create([
            'tin_pes_id' => $pessoa->pes_id,
            'tin_tit_id' => $titulacao->tit_id,
        ]);

        factory(TitulacaoInformacao::class)->create([
            'tin_pes_id' => $sndPessoa->pes_id,
            'tin_tit_id' => $titulacao->tit_id,
        ]);

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
        ], null, ['pes_id' => 'ASC']);

        $this->assertEquals(2, $result->count());
        $this->assertInstanceOf(TitulacaoInformacao::class, $result->first());
        $this->assertEquals($id, $result->first()->pes_id);

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
        ], null, ['pes_id' => 'DESC']);

        $this->assertEquals($sndId, $result->first()->pes_id);
    }

    public function testFindByWithSelectAndOrder()
    {
        factory(Pessoa::class, 10)->create();

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
            'pes_id' => 1
        ]);

        $this->assertEquals(0, $result->count());

        $pessoa = factory(Pessoa::class)->create();
        $id = $pessoa->pes_id;

        $sndPessoa = factory(Pessoa::class)->create();
        $sndId = $sndPessoa->pes_id;

        $titulacao = factory(Titulacao::class)->create([
            'tit_nome' => 'Doutorado'
        ]);

        factory(TitulacaoInformacao::class)->create([
            'tin_pes_id' => $pessoa->pes_id,
            'tin_tit_id' => $titulacao->tit_id,
        ]);

        factory(TitulacaoInformacao::class)->create([
            'tin_pes_id' => $sndPessoa->pes_id,
            'tin_tit_id' => $titulacao->tit_id,
        ]);

        $expectedAsc = [
            [
                'pes_id' => $pessoa->pes_id,
                'pes_nome' => $pessoa->pes_nome,
                'tit_nome' => $titulacao->tit_nome
            ],
            [
                'pes_id' => $sndPessoa->pes_id,
                'pes_nome' => $sndPessoa->pes_nome,
                'tit_nome' => $titulacao->tit_nome
            ]
        ];

        $expectedDesc = [
            $expectedAsc[1], $expectedAsc[0]
        ];

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
        ], ['pes_id', 'pes_nome', 'tit_nome'], ['pes_id' => 'ASC']);

        $this->assertEquals(2, $result->count());
        $this->assertEquals($expectedAsc, $result->toArray());

        $result = $this->repo->findBy([
            'tit_nome' => 'Doutorado',
        ], ['pes_id', 'pes_nome', 'tit_nome'], ['pes_id' => 'DESC']);

        $this->assertEquals(2, $result->count());
        $this->assertEquals($expectedDesc, $result->toArray());
    }
}
