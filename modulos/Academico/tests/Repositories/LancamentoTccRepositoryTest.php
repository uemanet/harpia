<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\LancamentoTcc;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\LancamentoTccRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;

class LancamentoTccRepositoryTest extends ModulosTestCase
{
    protected $modulodisciplinaRepository;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(LancamentoTccRepository::class);
        $this->modulodisciplinaRepository = $this->app->make(ModuloDisciplinaRepository::class);
        $this->table = 'acd_lancamentos_tccs';
    }

    public function testCreate()
    {
        $data = factory(LancamentoTcc::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(LancamentoTcc::class, $entry);
        $entryData = $entry->toArray();
        $entryData['ltc_data_apresentacao'] = $entry->getOriginal('ltc_data_apresentacao');
        $this->assertDatabaseHas($this->table, $entryData);
    }

    public function testFind()
    {
        $entry = factory(LancamentoTcc::class)->create();
        $id = $entry->ltc_id;
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['ltc_data_apresentacao'] = $entry->getOriginal('ltc_data_apresentacao');

        $fromRepositoryData = $fromRepository->toArray();
        $fromRepositoryData['ltc_data_apresentacao'] = $fromRepository->getOriginal('ltc_data_apresentacao');

        $this->assertInstanceOf(LancamentoTcc::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepositoryData);
        $this->assertEquals($entryData, $fromRepositoryData);
    }

    public function testUpdate()
    {
        $entry = factory(LancamentoTcc::class)->create();
        $id = $entry->ltc_id;

        $data = $entry->toArray();

        $data['ltc_titulo'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);
        $data['ltc_data_apresentacao'] = $entry->getOriginal('ltc_data_apresentacao');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(LancamentoTcc::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->getOriginal());
    }

    public function testDelete()
    {
        $entry = factory(LancamentoTcc::class)->create();
        $id = $entry->ltc_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(LancamentoTcc::class, 2)->create();

        $model = new LancamentoTcc();
        $expected = $model->pluck('ltc_titulo', 'ltc_id');
        $fromRepository = $this->repo->lists('ltc_id', 'ltc_titulo');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(LancamentoTcc::class, 2)->create();

        factory(LancamentoTcc::class)->create([
            'ltc_titulo' => 'Tcc'
        ]);

        $searchResult = $this->repo->search(array(['ltc_titulo', '=', 'Tcc']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(LancamentoTcc::class, 2)->create();

        $entry = factory(LancamentoTcc::class)->create([
            'ltc_titulo' => "Tcc"
        ]);

        $expected = [
            'ltc_id' => $entry->ltc_id,
            'ltc_titulo' => $entry->ltc_titulo
        ];

        $searchResult = $this->repo->search(array(['ltc_titulo', '=', "Tcc"]), ['ltc_id', 'ltc_titulo']);

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
        $created = factory(LancamentoTcc::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(LancamentoTcc::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new LancamentoTcc();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(LancamentoTcc::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(LancamentoTcc::class, 2)->create();

        $sort = [
            'field' => 'ltc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->ltc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(LancamentoTcc::class, 2)->create();
        factory(LancamentoTcc::class)->create([
            'ltc_titulo' => 'Tcc',
        ]);

        $search = [
            [
                'field' => 'ltc_titulo',
                'type' => '=',
                'term' => 'Tcc'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Tcc', $response->first()->ltc_titulo);
    }

    public function testPaginateRequest()
    {
        factory(LancamentoTcc::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ltc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testUpdateReturn0()
    {
        $response = $this->repo->update([], 1, 'ltc_id');

        $this->assertEquals(0, $response);
    }

    public function testfindBy()
    {
        $data = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();

        $response = $this->repo->findBy(
            ['ltc_mof_id' => $data->ltc_mof_id],
            ['ltc_titulo', 'ltc_tipo', 'ltc_data_apresentacao', 'ltc_observacao', 'pes_nome', 'pes_id']
        );
        $this->assertEquals($data->ltc_titulo, $response[0]->ltc_titulo);
        $this->assertEquals(1, count($response));
        $this->assertNotEmpty($response);
    }

    public function testdeleteAnexoTcc()
    {
        $data = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();
        $response = $this->repo->deleteAnexoTcc($data->ltc_id);

        $this->assertNotEquals(false, $response);
    }

    public function testdeleteReturnException()
    {
        $this->expectException(\Exception::class);
        $this->repo->deleteAnexoTcc(['exception']);
    }

    public function testdeleteAnexoTccReturnFalse()
    {
        $response = $this->repo->deleteAnexoTcc(1);

        $this->assertEquals(false, $response);
    }

    public function testUpdateWithoutAttribute()
    {
        $data = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();

        $updateArray = $data->toArray();
        $updateArray['ltc_nome'] = 'abcde_edcba';

        $lancamentotccId = $updateArray['ltc_id'];
        unset($updateArray['ltc_id']);

        $response = $this->repo->update($updateArray, $lancamentotccId);

        $this->assertEquals(1, $response);
    }

    public function testFindDisciplinaByTurma()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create(['mdc_tipo_disciplina' => 'tcc']);

        $OfertaCurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $modulodisciplina->modulo->matriz->curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $OfertaCurso->ofc_id
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_trm_id' => $turma->trm_id,
            'ofd_mdc_id' => $modulodisciplina->mdc_id,
        ]);

        $response = $this->repo->findDisciplinaByTurma($turma->trm_id);

        $this->assertNotEmpty($response, '');
    }

}
