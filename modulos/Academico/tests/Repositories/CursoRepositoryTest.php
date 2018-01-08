<?php
declare(strict_types=1);

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Curso;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\Vinculo;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Repositories\CursoRepository;

class CursoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(CursoRepository::class);
        $this->table = 'acd_cursos';
    }

    private function mockVinculo(int $qtdCursos = 1, $withBond = true)
    {
        // Usuario, Curso e Vinculo
        $user = factory(Usuario::class)->create();

        $cursos = factory(Curso::class, $qtdCursos)->create();

        if ($withBond) {
            foreach ($cursos as $curso) {
                factory(Vinculo::class)->create([
                    'ucr_usr_id' => $user->usr_id,
                    'ucr_crs_id' => $curso->crs_id
                ]);
            }
        }

        return [$user, $cursos];
    }

    private function mockConfigs()
    {
        $modos = [
            'substituir_menor_nota',
            'substituir_media_final'
        ];

        return [
            'media_min_aprovacao' => 7,
            'media_min_final' => 5,
            'media_min_aprovacao_final' => 5,
            'modo_recuperacao' => $modos[random_int(0, 1)],
            'conceitos_aprovacao' => json_encode(['Bom', 'Muito Bom'], JSON_UNESCAPED_UNICODE)
        ];
    }

    public function testCreate()
    {
        // Necessario estar logado por conta do Vinculo
        $user = factory(Usuario::class)->create();
        $this->actingAs($user);

        $curso = factory(Curso::class)->raw();
        $configs = $this->mockConfigs();

        $data = array_merge($curso, $configs);

        $this->assertEquals(0, Curso::all()->count());
        $this->assertEquals(0, ConfiguracaoCurso::all()->count());
        $return = $this->repo->create($data);

        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('success', $return['status']);
        $this->assertEquals(1, Curso::all()->count());
        $this->assertEquals(5, ConfiguracaoCurso::all()->count());
    }

    public function testFind()
    {
        $entry = factory(Curso::class)->create();
        $id = $entry->crs_id;
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['crs_data_autorizacao'] = $entry->getOriginal('crs_data_autorizacao');

        $data = $fromRepository->toArray();
        $data['crs_data_autorizacao'] = $fromRepository->getOriginal('crs_data_autorizacao');

        $this->assertInstanceOf(Curso::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertEquals($entryData, $data);
    }

    public function testUpdate()
    {
        $entry = factory(Curso::class)->create();
        $id = $entry->crs_id;

        $data = $entry->toArray();
        $data['crs_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $entry = Curso::find($id);
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['crs_data_autorizacao'] = $entry->getOriginal('crs_data_autorizacao');

        $data = $fromRepository->toArray();
        $data['crs_data_autorizacao'] = $fromRepository->getOriginal('crs_data_autorizacao');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Curso::class, $fromRepository);
        $this->assertEquals($entryData, $data);
    }

    public function testDelete()
    {
        $entry = factory(Curso::class)->create();
        $id = $entry->crs_id;

        $return = $this->repo->delete($id);

        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('success', $return['status']);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Curso::class, 2)->create();

        list($user, $cursos) = $this->mockVinculo(2);

        $this->actingAs($user);

        // Traz apenas os cursos com vinculo
        $fromRepository = $this->repo->lists('crs_id', 'crs_nome');
        $this->assertEquals($cursos->pluck('crs_nome', 'crs_id')->toArray(), $fromRepository);

        // Traz todos os cursos
        $model = new Curso();
        $expected = $model->pluck('crs_nome', 'crs_id');
        $fromRepository = $this->repo->lists('crs_id', 'crs_nome', true);

        $this->assertEquals($expected->toArray(), $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Curso::class, 2)->create();

        factory(Curso::class)->create([
            'crs_nome' => 'centro'
        ]);

        $searchResult = $this->repo->search(array(['crs_nome', '=', 'centro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Curso::class, 2)->create();

        $entry = factory(Curso::class)->create([
            'crs_nome' => "centro"
        ]);

        $expected = [
            'crs_id' => $entry->crs_id,
            'crs_nome' => $entry->crs_nome
        ];

        $searchResult = $this->repo->search(array(['crs_nome', '=', "centro"]), ['crs_id', 'crs_nome']);

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
        $created = factory(Curso::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Curso::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Curso();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Curso::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Curso::class, 2)->create();

        $sort = [
            'field' => 'crs_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->crs_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Curso::class, 2)->create();
        factory(Curso::class)->create([
            'crs_nome' => 'centro',
        ]);

        $search = [
            [
                'field' => 'crs_nome',
                'type' => '=',
                'term' => 'centro'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('centro', $response->first()->crs_nome);
    }

    public function testPaginateRequest()
    {
        factory(Curso::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'crs_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
