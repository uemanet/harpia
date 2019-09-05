<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\OfertaDisciplina;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;

class OfertaDisciplinaRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(OfertaDisciplinaRepository::class);
        $this->table = 'acd_ofertas_disciplinas';
    }

    public function testCreate()
    {
        $data = factory(OfertaDisciplina::class)->raw();
        $entry = $this->repo->create($data);

        $entryData = $entry->toArray();
        $entryData['ofd_tipo_avaliacao'] = $entry->getOriginal('ofd_tipo_avaliacao');

        $this->assertInstanceOf(OfertaDisciplina::class, $entry);
        $this->assertDatabaseHas($this->table, $entryData);
    }

    public function testFind()
    {
        $entry = factory(OfertaDisciplina::class)->create();
        $entryData = $entry->toArray();
        $entryData['ofd_tipo_avaliacao'] = $entry->getOriginal('ofd_tipo_avaliacao');

        $id = $entry->ofd_id;
        $fromRepository = $this->repo->find($id);

        $fromRepositoryData = $fromRepository->toArray();
        $fromRepositoryData['ofd_tipo_avaliacao'] = $fromRepository->getOriginal('ofd_tipo_avaliacao');

        $this->assertInstanceOf(OfertaDisciplina::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepositoryData);
        $this->assertEquals($entryData, $fromRepositoryData);
    }

    public function testUpdate()
    {
        $entry = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'conceitual'
        ]);
        $id = $entry->ofd_id;

        $data = $entry->toArray();
        $data['ofd_tipo_avaliacao'] = "numerica";

        $return = $this->repo->update($data, $id);

        $fromRepository = $this->repo->find($id);
        $fromRepositoryData = $fromRepository->toArray();
        $fromRepositoryData['ofd_tipo_avaliacao'] = $fromRepository->getOriginal('ofd_tipo_avaliacao');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(OfertaDisciplina::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryData);
    }

    public function testDelete()
    {
        $entry = factory(OfertaDisciplina::class)->create();
        $id = $entry->ofd_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(OfertaDisciplina::class, 2)->create();

        $model = new OfertaDisciplina();
        $expected = $model->pluck('ofd_tipo_avaliacao', 'ofd_id');
        $fromRepository = $this->repo->lists('ofd_id', 'ofd_tipo_avaliacao');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(OfertaDisciplina::class, 2)->create();

        factory(OfertaDisciplina::class)->create([
            'ofd_qtd_vagas' => 60
        ]);

        $searchResult = $this->repo->search(array(['ofd_qtd_vagas', '=', 60]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(OfertaDisciplina::class, 2)->create([
            'ofd_tipo_avaliacao' => "numerica"
        ]);

        $entry = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => "conceitual"
        ]);

        $expected = [
            'ofd_id' => $entry->ofd_id,
            'ofd_tipo_avaliacao' => $entry->ofd_tipo_avaliacao
        ];

        $searchResult = $this->repo->search(array(['ofd_tipo_avaliacao', '=', "conceitual"]), ['ofd_id', 'ofd_tipo_avaliacao']);

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
        $created = factory(OfertaDisciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(OfertaDisciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new OfertaDisciplina();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(OfertaDisciplina::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(OfertaDisciplina::class, 2)->create();

        $sort = [
            'field' => 'ofd_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->ofd_id);
    }

    public function testPaginateWithSearch()
    {
        factory(OfertaDisciplina::class, 2)->create([
            'ofd_tipo_avaliacao' => "conceitual"
        ]);

        factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica',
        ]);

        $search = [
            [
                'field' => 'ofd_tipo_avaliacao',
                'type' => '=',
                'term' => 'numerica'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('numerica', $response->first()->getOriginal('ofd_tipo_avaliacao'));
    }

    public function testPaginateRequest()
    {
        factory(OfertaDisciplina::class, 3)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ofd_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertGreaterThan(0, $response->total());
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(3, $response->first()->ofd_id);
    }

    public function testFindAll()
    {
        $ofertas = factory(OfertaDisciplina::class, 10)->create();

        // Buscar todas sem opcoes, select ou ordenacao
        $result = $this->repo->findAll();

        $this->assertEquals($ofertas->count(), $result->count());

        // Com options
        $options = [
            'ofd_id' => 3
        ];

        $result = $this->repo->findAll($options);
        $this->assertEquals(1, $result->count());
        $this->assertEquals(3, $result->first()->ofd_id);

        // Com select
        $select = [
            'ofd_id', 'ofd_tipo_avaliacao'
        ];

        $result = $this->repo->findAll([], $select);
        $this->assertEquals($ofertas->count(), $result->count());

        $resultArray = $result->random()->toArray();

        $this->assertTrue(array_key_exists('ofd_id', $resultArray));
        $this->assertTrue(array_key_exists('ofd_tipo_avaliacao', $resultArray));
        $this->assertFalse(array_key_exists('ofd_qtd_vagas', $resultArray));

        // Com order
        $order = [
            'ofd_id' => 'desc'
        ];

        $result = $this->repo->findAll([], [], $order);
        $this->assertEquals($ofertas->count(), $result->count());
        $this->assertGreaterThan($result->last()->ofd_id, $result->first()->ofd_id);
    }

    public function testCountMatriculadosByOferta()
    {
        $data = factory(OfertaDisciplina::class)->create();
        $matriculasofertas = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class, 10)->create(['mof_ofd_id' => $data->ofd_id]);
        $response = $this->repo->countMatriculadosByOferta($data->ofd_id);

        $this->assertEquals($matriculasofertas->count(), $response);
    }

    public function testVerifyDisciplinaTurmaPeriodo()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $ofertaCurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $ofertaCurso->ofc_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $ofertaCurso->ofc_mtc_id
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id
        ]);

        // Oferta nao existe
        $response = $this->repo->verifyDisciplinaTurmaPeriodo($turma->trm_id, $turma->trm_per_id, $moduloDisciplina->mdc_id);
        $this->assertFalse($response);

        // Oferta existe
        $data = factory(OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
        ]);

        $response = $this->repo->verifyDisciplinaTurmaPeriodo($data->ofd_trm_id, $data->ofd_per_id, $data->ofd_mdc_id);
        $this->assertTrue($response);
    }

    public function testFindAllWithMapeamentoNotas()
    {
        $ofertas = factory(OfertaDisciplina::class, 10)->create();

        // Buscar todas sem opcoes, select ou ordenacao
        $result = $this->repo->findAllWithMapeamentoNotas();

        $this->assertEquals($ofertas->count(), $result->count());

        // Com options
        $options = [
            'ofd_id' => 3
        ];

        $result = $this->repo->findAllWithMapeamentoNotas($options);
        $this->assertEquals(1, $result->count());
        $this->assertEquals(3, $result->first()->ofd_id);

        // Com select
        $select = [
            'ofd_id', 'ofd_tipo_avaliacao', 'min_ofd_id'
        ];

        $result = $this->repo->findAllWithMapeamentoNotas([], $select);
        $this->assertEquals($ofertas->count(), $result->count());

        $resultArray = $result->random()->toArray();

        $this->assertTrue(array_key_exists('ofd_id', $resultArray));
        $this->assertTrue(array_key_exists('ofd_tipo_avaliacao', $resultArray));
        $this->assertTrue(array_key_exists('min_ofd_id', $resultArray));
        $this->assertFalse(array_key_exists('ofd_qtd_vagas', $resultArray));

        // Com order
        $order = [
            'ofd_id' => 'desc'
        ];

        $result = $this->repo->findAllWithMapeamentoNotas([], [], $order);
        $this->assertEquals($ofertas->count(), $result->count());
        $this->assertGreaterThan($result->last()->ofd_id, $result->first()->ofd_id);

        $response = $this->repo->findAllWithMapeamentoNotas($options, $select, $order);
        $this->assertNotEmpty($response);
    }
}
