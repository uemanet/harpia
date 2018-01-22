<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Diploma;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Academico\Repositories\DiplomaRepository;

class DiplomaRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(DiplomaRepository::class);
        $this->table = 'acd_diplomas';
    }

    public function testCreate()
    {
        $data = factory(Diploma::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Diploma::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Diploma::class)->create();
        $id = $entry->dip_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Diploma::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Diploma::class)->create();
        $id = $entry->dip_id;

        $data = $entry->toArray();

        $data['dip_processo'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Diploma::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Diploma::class)->create();
        $id = $entry->dip_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Diploma::class, 2)->create();

        $model = new Diploma();
        $expected = $model->pluck('dip_processo', 'dip_id');
        $fromRepository = $this->repo->lists('dip_id', 'dip_processo');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Diploma::class, 2)->create();

        factory(Diploma::class)->create([
            'dip_processo' => '1564879'
        ]);

        $searchResult = $this->repo->search(array(['dip_processo', '=', '1564879']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Diploma::class, 2)->create();

        $entry = factory(Diploma::class)->create([
            'dip_processo' => "1564879"
        ]);

        $expected = [
            'dip_id' => $entry->dip_id,
            'dip_processo' => $entry->dip_processo
        ];

        $searchResult = $this->repo->search(array(['dip_processo', '=', "1564879"]), ['dip_id', 'dip_processo']);

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
        $created = factory(Diploma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Diploma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Diploma();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Diploma::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Diploma::class, 2)->create();

        $sort = [
            'field' => 'dip_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->dip_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Diploma::class, 2)->create();
        factory(Diploma::class)->create([
            'dip_processo' => '1564879',
        ]);

        $search = [
            [
                'field' => 'dip_processo',
                'type' => '=',
                'term' => '1564879'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('1564879', $response->first()->dip_processo);
    }

    public function testPaginateWithSortAndSearch()
    {
        factory(Diploma::class, 2)->create();
        factory(Diploma::class, 2)->create([
            'dip_processo' => '1564879',
        ]);

        $sort = [
            'field' => 'dip_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'dip_processo',
                'type' => '=',
                'term' => '1564879'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());

        // Desc order
        $this->assertGreaterThan($response->last()->dip_id, $response->first()->dip_id);
        $this->assertEquals('1564879', $response->first()->dip_processo);
    }

    public function testPaginateRequest()
    {
        factory(Diploma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'dip_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetAlunosDiplomados()
    {
        factory(Diploma::class, 2)->create();

        $diplomados = $this->repo->getAlunosDiplomados(1);

        $this->assertNotEmpty($diplomados['diplomados']);
        $this->assertEmpty($diplomados['aptos']);
    }

    public function testGetAlunosDiplomadosWithPolo()
    {
        $diploma = factory(Diploma::class)->create();

        $diplomados = $this->repo->getAlunosDiplomados($diploma->matricula->mat_trm_id, $diploma->matricula->mat_pol_id);

        $this->assertNotEmpty($diplomados['diplomados']);
        $this->assertEmpty($diplomados['aptos']);
    }

    public function testGetPrintData()
    {
        $diploma = factory(Diploma::class, 2)->create();

        $id = array(0 => $diploma[0]->dip_id);
        $idPessoa = $diploma[0]->matricula->aluno->pessoa->pes_id;

        $docRepository = $this->app->make(DocumentoRepository::class);
        $documento = $docRepository->create(['doc_pes_id' => $idPessoa, 'doc_tpd_id' => 1, 'doc_conteudo' => 4653673163, 'doc_orgao' => 'SSP']);

        $diplomados = $this->repo->getPrintData($id);

        $this->assertNotEmpty($diplomados);
    }

    public function testGetPrintDataReturnError()
    {
        $diploma = factory(Diploma::class, 2)->create();

        $id = array(0 => $diploma[0]->dip_id);
        $idPessoa = $diploma[0]->matricula->aluno->pessoa->pes_id;

        $docRepository = $this->app->make(DocumentoRepository::class);
        ;
        //criado um documento sem orgao para fazer o teste. É esperado um documento um erro no campo órgão como retorno
        $documento = $docRepository->create(['doc_pes_id' => $idPessoa, 'doc_tpd_id' => 1, 'doc_conteudo' => 4653673163]);
        $diplomados = $this->repo->getPrintData($id);

        $this->assertEquals($diplomados['type'], 'error');
        $this->assertEquals($diplomados['campo'], 'ORGAO');
    }

    public function testGetPrintDataReturnNull()
    {
        $diplomados = $this->repo->getPrintData([1]);

        $this->assertEquals(null, $diplomados);
    }
}
