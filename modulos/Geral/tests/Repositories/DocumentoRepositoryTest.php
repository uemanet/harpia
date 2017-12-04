<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Documento;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\DocumentoRepository;

class DocumentoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(DocumentoRepository::class);
        $this->table = 'gra_documentos';
    }

    public function testCreate()
    {
        $data = factory(Documento::class)->raw();
        $entry = $this->repo->create($data);

        // Accessor
        $checkData = $data;
        $checkData['doc_data_expedicao'] = $entry->getOriginal('doc_data_expedicao');

        $this->assertInstanceOf(Documento::class, $entry);
        $this->assertDatabaseHas($this->table, $checkData);
    }

    public function testFind()
    {
        $entry = factory(Documento::class)->create();
        $id = $entry->doc_id;
        $fromRepository = $this->repo->find($id);

        $data = $entry->toArray();
        // Accessor
        $data['doc_data_expedicao'] = $entry->getOriginal('doc_data_expedicao');

        $fromRepositoryArray = $fromRepository->toArray();
        $fromRepositoryArray['doc_data_expedicao'] = $fromRepository->getOriginal('doc_data_expedicao');

        $this->assertInstanceOf(Documento::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepositoryArray);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testUpdate()
    {
        $entry = factory(Documento::class)->create();
        $id = $entry->doc_id;

        $data = $entry->toArray();

        $data['doc_conteudo'] = "15648989878";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        // Accessor
        $data['doc_data_expedicao'] = $entry->getOriginal('doc_data_expedicao');

        $fromRepositoryArray = $fromRepository->toArray();
        $fromRepositoryArray['doc_data_expedicao'] = $fromRepository->getOriginal('doc_data_expedicao');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Documento::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testDelete()
    {
        $entry = factory(Documento::class)->create();
        $id = $entry->doc_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Documento::class, 2)->create();

        $model = new Documento();
        $expected = $model->pluck('doc_conteudo', 'doc_id');
        $fromRepository = $this->repo->lists('doc_id', 'doc_conteudo');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Documento::class, 2)->create();

        factory(Documento::class)->create([
            'doc_conteudo' => '156455'
        ]);

        $searchResult = $this->repo->search(array(['doc_conteudo', '=', '156455']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Documento::class, 2)->create();

        $entry = factory(Documento::class)->create([
            'doc_conteudo' => "15648987"
        ]);

        $expected = [
            'doc_id' => $entry->doc_id,
            'doc_conteudo' => $entry->doc_conteudo
        ];

        $searchResult = $this->repo->search(array(['doc_conteudo', '=', "15648987"]), ['doc_id', 'doc_conteudo']);

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
        $created = factory(Documento::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Documento::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Documento();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Documento::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Documento::class, 2)->create();

        $sort = [
            'field' => 'doc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->doc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Documento::class, 2)->create();
        factory(Documento::class)->create([
            'doc_conteudo' => '654897894',
        ]);

        $search = [
            [
                'field' => 'doc_conteudo',
                'type' => '=',
                'term' => '654897894'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('654897894', $response->first()->doc_conteudo);
    }

    public function testPaginateRequest()
    {
        factory(Documento::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'doc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyCpfReturnsFalse()
    {
        $entries = factory(Documento::class, 10)->create();

        factory(Documento::class)->create([
            'doc_conteudo' => '60721694315'
        ]);

        $cpf = $this->repo->verifyCpf($entries[0]->doc_conteudo, $entries[0]->pessoa->pes_id);

        $this->assertEquals(false, $cpf);
    }

    public function testVerifyCpfReturnsTrue()
    {
        $entries = factory(Documento::class, 10)->create();

        factory(Documento::class)->create([
            'doc_conteudo' => '60721694315'
        ]);

        $cpf = $this->repo->verifyCpf($entries[0]->doc_conteudo, $entries[2]->pessoa->pes_id);

        $this->assertEquals(true, $cpf);
    }

    public function testUpdateDocumento()
    {
        factory(Documento::class, 5)->create([
            'doc_orgao' => 'TDD'
        ]);

        $data = [
          'doc_conteudo' => '216752714'
        ];

        $return = $this->repo->updateDocumento($data, [
            'doc_orgao' => 'TDD'
        ]);

        $this->assertEquals(5, $return);

        $return = $this->repo->updateDocumento($data, [
            'doc_orgao' => 'ANY'
        ]);

        $this->assertEquals(0, $return);
    }

    public function testDeleteDocumento()
    {
        $entry = factory(Documento::class)->create();
        $id = $entry->doc_id;

        $return = (bool) $this->repo->deleteDocumento($id);

        $fromRepository = $this->repo->find($id);

        // Accessor
        $data = $entry->toArray();
        $data['doc_data_expedicao'] = $entry->getOriginal('doc_data_expedicao');

        $fromRepositoryArray = $fromRepository->toArray();
        $fromRepositoryArray['doc_data_expedicao'] = $fromRepository->getOriginal('doc_data_expedicao');

        $this->assertTrue($return);
        $this->assertDatabaseMissing($this->table, $data);
        $this->assertDatabaseHas($this->table, $fromRepositoryArray);
    }
}
