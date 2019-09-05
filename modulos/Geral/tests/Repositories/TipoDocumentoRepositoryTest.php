<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Pessoa;
use Illuminate\Support\Collection;
use Modulos\Geral\Models\Documento;
use Modulos\Geral\Models\TipoDocumento;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\TipoDocumentoRepository;

class TipoDocumentoRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(TipoDocumentoRepository::class);
        $this->table = 'gra_tipos_documentos';
    }

    public function testCreate()
    {
        $data = factory(TipoDocumento::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(TipoDocumento::class, $entry);
        $this->assertDatabaseHas($this->table, $data);
    }

    public function testFind()
    {
        $entry = factory(TipoDocumento::class)->create();
        $id = $entry->tpd_id;
        $fromRepository = $this->repo->find($id);

        $data = $entry->toArray();
        $fromRepositoryArray = $fromRepository->toArray();

        $this->assertInstanceOf(TipoDocumento::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepositoryArray);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testUpdate()
    {
        $entry = factory(TipoDocumento::class)->create();
        $id = $entry->tpd_id;

        $data = $entry->toArray();

        $data['tpd_nome'] = "novoNome";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $fromRepositoryArray = $fromRepository->toArray();

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(TipoDocumento::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testDelete()
    {
        $entry = factory(TipoDocumento::class)->create();
        $id = $entry->tpd_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(TipoDocumento::class, 2)->create();

        $model = new TipoDocumento();
        $expected = $model->pluck('tpd_nome', 'tpd_id');
        $fromRepository = $this->repo->lists('tpd_id', 'tpd_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(TipoDocumento::class, 2)->create();

        factory(TipoDocumento::class)->create([
            'tpd_nome' => 'tipoDoc'
        ]);

        $searchResult = $this->repo->search(array(['tpd_nome', '=', 'tipoDoc']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(TipoDocumento::class, 2)->create();

        $entry = factory(TipoDocumento::class)->create([
            'tpd_nome' => "tipoDoc"
        ]);

        $expected = [
            'tpd_id' => $entry->tpd_id,
            'tpd_nome' => $entry->tpd_nome
        ];

        $searchResult = $this->repo->search(array(['tpd_nome', '=', "tipoDoc"]), ['tpd_id', 'tpd_nome']);

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
        $created = factory(TipoDocumento::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(TipoDocumento::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new TipoDocumento();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(TipoDocumento::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(TipoDocumento::class, 2)->create();

        $sort = [
            'field' => 'tpd_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->tpd_id);
    }

    public function testPaginateWithSearch()
    {
        factory(TipoDocumento::class, 2)->create();
        factory(TipoDocumento::class)->create([
            'tpd_nome' => 'tipoDoc',
        ]);

        $search = [
            [
                'field' => 'tpd_nome',
                'type' => '=',
                'term' => 'tipoDoc'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('tipoDoc', $response->first()->tpd_nome);
    }

    public function testPaginateRequest()
    {
        factory(TipoDocumento::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'tpd_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testListsTipoDocumentoByDocumentoId()
    {
        $tipo = factory(TipoDocumento::class)->create([
            'tpd_nome' => 'RG'
        ]);

        $expected = [
            $tipo->tpd_id => $tipo->tpd_nome
        ];

        $documento = factory(Documento::class)->create([
            'doc_tpd_id' => $tipo->tpd_id,
        ]);

        $id = $documento->doc_id;

        $return = $this->repo->listsTipoDocumentoByDocumentoId($id);

        $this->assertTrue(is_array($return));
        $this->assertEquals($expected, $return);
    }

    public function testListsTiposDocumentosWithoutPessoa()
    {
        $tipoAnteriores = factory(TipoDocumento::class, 3)->create();

        $tipo = factory(TipoDocumento::class)->create([
            'tpd_nome' => 'RG'
        ]);

        $expected = $tipoAnteriores->pluck('tpd_nome', 'tpd_id')->toArray();

        $documento = factory(Documento::class)->create([
            'doc_tpd_id' => $tipo->tpd_id,
        ]);

        $id = $documento->doc_id;

        $return = $this->repo->listsTiposDocumentosWithoutPessoa($id);

        $this->assertInstanceOf(Collection::class, $return);
        $this->assertEquals($expected, $return->toArray());
    }
}
