<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\MatrizCurricular;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\MatrizCurricularRepository;

class MatrizCurricularRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(MatrizCurricularRepository::class);
        $this->table = 'acd_matrizes_curriculares';
    }

    public function mockData(Curso $curso, array $matriz = [])
    {
        $matriz = array_merge($matriz, ['mtc_crs_id' => $curso->crs_id]);
        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create($matriz);

        $modulosMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        // Disciplinas para o curso
        $disciplinas = factory(Modulos\Academico\Models\Disciplina::class, 6)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $modulosDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosMatriz as $key => $moduloMatriz) {
            for ($i = $key * 3; $i < 3 * ($key + 1); $i++) {
                $modulosDisciplina[] = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
                    'mdc_dis_id' => $disciplinas[$i]->dis_id,
                    'mdc_mdo_id' => $moduloMatriz->mdo_id,
                    'mdc_tipo_disciplina' => 'obrigatoria'
                ]);
            }
        }

        return [$matrizCurricular, $modulosMatriz, $disciplinas];
    }

    public function testCreate()
    {
        $data = factory(MatrizCurricular::class)->raw();
        $entry = $this->repo->create($data);

        $fromRepository = $entry->toArray();
        $fromRepository['mtc_data'] = $entry->getOriginal('mtc_data');

        $this->assertInstanceOf(MatrizCurricular::class, $entry);
        $this->assertDatabaseHas($this->table, $fromRepository);
    }

    public function testFind()
    {
        $entry = factory(MatrizCurricular::class)->create();
        $id = $entry->mtc_id;
        $fromRepository = $this->repo->find($id);

        $fromRepositoryArray = $entry->toArray();
        $fromRepositoryArray['mtc_data'] = $entry->getOriginal('mtc_data');

        $this->assertInstanceOf(MatrizCurricular::class, $fromRepository);

        $this->assertDatabaseHas($this->table, $fromRepositoryArray);
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(MatrizCurricular::class)->create();
        $id = $entry->mtc_id;

        $data = $entry->toArray();

        $data['mtc_titulo'] = "Title";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $fromRepositoryArray = $fromRepository->toArray();

        $data['mtc_data'] = $entry->getOriginal('mtc_data');
        $fromRepositoryArray['mtc_data'] = $entry->getOriginal('mtc_data');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(MatrizCurricular::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testDelete()
    {
        $entry = factory(MatrizCurricular::class)->create();
        $id = $entry->mtc_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(MatrizCurricular::class, 2)->create();

        $model = new MatrizCurricular();
        $expected = $model->pluck('mtc_titulo', 'mtc_id');
        $fromRepository = $this->repo->lists('mtc_id', 'mtc_titulo');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(MatrizCurricular::class, 2)->create();

        factory(MatrizCurricular::class)->create([
            'mtc_titulo' => 'centro'
        ]);

        $searchResult = $this->repo->search(array(['mtc_titulo', '=', 'centro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(MatrizCurricular::class, 2)->create();

        $entry = factory(MatrizCurricular::class)->create([
            'mtc_titulo' => "centro"
        ]);

        $expected = [
            'mtc_id' => $entry->mtc_id,
            'mtc_titulo' => $entry->mtc_titulo
        ];

        $searchResult = $this->repo->search(array(['mtc_titulo', '=', "centro"]), ['mtc_id', 'mtc_titulo']);

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
        $created = factory(MatrizCurricular::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(MatrizCurricular::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new MatrizCurricular();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(MatrizCurricular::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(MatrizCurricular::class, 2)->create();

        $sort = [
            'field' => 'mtc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mtc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(MatrizCurricular::class, 2)->create();
        factory(MatrizCurricular::class)->create([
            'mtc_titulo' => 'centro',
        ]);

        $search = [
            [
                'field' => 'mtc_titulo',
                'type' => '=',
                'term' => 'centro'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('centro', $response->first()->mtc_titulo);
    }

    public function testPaginateRequest()
    {
        factory(MatrizCurricular::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mtc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequestByCurso()
    {
        $matrizes = collect([]);
        $curso = factory(Curso::class)->create();

        // Duas matrizes para o mesmo curso
        list($matriz, ) = $this->mockData($curso, ['mtc_titulo' => 'Taylor']);
        $matrizes[] = $matriz;

        list($matriz, ) = $this->mockData($curso, ['mtc_titulo' => 'May']);
        $matrizes[] = $matriz;

        list($matriz, ) = $this->mockData($curso, ['mtc_titulo' => 'Deacon']);
        $matrizes[] = $matriz;

        // Paginacao sem request params
        $result = $this->repo->paginateRequestByCurso($curso->crs_id);
        $this->assertEquals($matrizes->count(), $result->count());

        $requestParams = [
            'field' => 'mtc_titulo',
            'sort' => 'desc'
        ];

        // Paginacao com request params
        $result = $this->repo->paginateRequestByCurso($curso->crs_id, $requestParams);
        $this->assertEquals($matrizes->count(), $result->count());
        $this->assertEquals('Taylor', $result->first()->mtc_titulo);
    }

    public function testFindAllByCurso()
    {
        $matrizes = collect([]);
        $curso = factory(Curso::class)->create();

        // Duas matrizes para o mesmo curso
        list($matriz, ) = $this->mockData($curso, ['mtc_titulo' => 'Taylor']);
        $matrizes[] = $matriz;

        list($matriz, ) = $this->mockData($curso, ['mtc_titulo' => 'May']);
        $matrizes[] = $matriz;

        list($matriz, ) = $this->mockData($curso, ['mtc_titulo' => 'Deacon']);
        $matrizes[] = $matriz;

        // Mais um curso e mais uma matriz
        $outroCurso = factory(Curso::class)->create();
        $this->mockData($outroCurso, ['mtc_titulo' => 'Mercury']);

        $result = $this->repo->findAllByCurso($curso->crs_id);

        $this->assertEquals($matrizes->count(), $result->count());
        $this->assertEquals($matrizes->pluck('mtc_id')->toArray(), $result->pluck('mtc_id')->toArray());
    }

    public function testFindByOfertaCurso()
    {
        $curso = factory(Curso::class)->create();

        // Duas matrizes para o curso
        list($primeiraMatriz, , $disciplinasPrimeiraMatriz) = $this->mockData($curso);
        list($segundaMatriz, , $disciplinasSegundaMatriz) = $this->mockData($curso);

        $ofertaCurso = factory(\Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $primeiraMatriz->mtc_id
        ]);

        $result = $this->repo->findByOfertaCurso($ofertaCurso->ofc_id);

        $this->assertInstanceOf(MatrizCurricular::class, $result);
        $this->assertEquals($primeiraMatriz->toArray(), $result->toArray());
    }

    public function testGetDisciplinasByMatrizId()
    {
        $curso = factory(Curso::class)->create();
        list($matriz, , $disciplinas) = $this->mockData($curso, ['mtc_titulo' => 'Taylor']);

        // Sem opcoes
        $id = $matriz->mtc_id;
        $result = $this->repo->getDisciplinasByMatrizId($id);

        $this->assertEquals($disciplinas->count(), $result->count());

        // Com opcoes
        $options = [
            'mdo_id' => 2
        ];

        $result = $this->repo->getDisciplinasByMatrizId($id, $options);

        $this->assertEquals(3, $result->count());
    }

    public function testVerifyIfDisciplinaExistsInMatriz()
    {
        $curso = factory(Curso::class)->create();
        list($matriz, $modulosMatriz, $disciplinas) = $this->mockData($curso, ['mtc_titulo' => 'Taylor']);
        $id = $matriz->mtc_id;

        // Cadastra disciplina TCC
        $disciplinaTcc = factory(\Modulos\Academico\Models\Disciplina::class)->create();

        factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplinaTcc->dis_id,
            'mdc_mdo_id' => $modulosMatriz->last()->mdo_id,
            'mdc_tipo_disciplina' => 'tcc'
        ]);

        $toCheck = $disciplinas->random();

        // Disciplina existente
        $result = $this->repo->verifyIfDisciplinaExistsInMatriz($id, $toCheck->dis_id);
        $this->assertTrue($result);

        // Disciplina existe, mas nao eh tcc
        $result = $this->repo->verifyIfDisciplinaExistsInMatriz($id, $toCheck->dis_id, true);
        $this->assertFalse($result);

        // Disciplina nao existe
        $disciplinaAvulsa = factory(\Modulos\Academico\Models\Disciplina::class)->create();

        $result = $this->repo->verifyIfDisciplinaExistsInMatriz($id, $disciplinaAvulsa->dis_id);
        $this->assertFalse($result);

        $result = $this->repo->verifyIfDisciplinaExistsInMatriz($id, $disciplinaAvulsa->dis_id, true);
        $this->assertFalse($result);

        // Disciplina existe e eh tcc
        $result = $this->repo->verifyIfDisciplinaExistsInMatriz($id, $disciplinaTcc->dis_id);
        $this->assertTrue($result);

        $result = $this->repo->verifyIfDisciplinaExistsInMatriz($id, $disciplinaTcc->dis_id, true);
        $this->assertTrue($result);
    }

    public function testVerifyIfNomeDisciplinaExistsInMatriz()
    {
        $curso = factory(Curso::class)->create();
        list($matriz, $modulosMatriz, $disciplinas) = $this->mockData($curso, ['mtc_titulo' => 'Taylor']);
        $id = $matriz->mtc_id;

        $nome = 'Algebra Linear';

        $result = $this->repo->verifyIfNomeDisciplinaExistsInMatriz($id, $nome);
        $this->assertFalse($result);

        // Cadastra disciplina
        $disciplinaNova = factory(\Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nome' => $nome
        ]);

        factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplinaNova->dis_id,
            'mdc_mdo_id' => $modulosMatriz->last()->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $result = $this->repo->verifyIfNomeDisciplinaExistsInMatriz($id, $nome);
        $this->assertTrue($result);
    }

    public function testVerifyIfExistsDisciplinaTccInMatriz()
    {
        $curso = factory(Curso::class)->create();
        list($matriz, $modulosMatriz) = $this->mockData($curso, ['mtc_titulo' => 'Taylor']);
        $id = $matriz->mtc_id;

        $result = $this->repo->verifyIfExistsDisciplinaTccInMatriz($id);
        $this->assertFalse($result);

        // Cadastra disciplina tcc
        $disciplinaTcc = factory(\Modulos\Academico\Models\Disciplina::class)->create();

        factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplinaTcc->dis_id,
            'mdc_mdo_id' => $modulosMatriz->last()->mdo_id,
            'mdc_tipo_disciplina' => 'tcc'
        ]);

        $result = $this->repo->verifyIfExistsDisciplinaTccInMatriz($id);
        $this->assertTrue($result);
    }
}
