<?php

use Tests\ModulosTestCase;
use Tests\Helpers\Reflection;
use Modulos\Geral\Models\Pessoa;
use Modulos\Academico\Models\Tutor;
use Modulos\Academico\Models\Curso;
use Modulos\Geral\Models\Documento;
use Modulos\Academico\Models\Vinculo;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\Matricula;
use Illuminate\Support\Facades\Artisan;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Academico\Models\TutorGrupo;


class TutorRepositoryTest extends ModulosTestCase
{
    use Reflection;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(TutorRepository::class);
        $this->table = 'acd_tutores';
    }

    public function testCreate()
    {
        $data = factory(Tutor::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Tutor::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Tutor::class)->create();
        $id = $entry->tut_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Tutor::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        factory(Pessoa::class, 10);
        $entry = factory(Tutor::class)->create();
        $id = $entry->tut_id;

        $data = $entry->toArray();

        $data['tut_pes_id'] = 5;

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Tutor::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Tutor::class)->create();
        $id = $entry->tut_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Tutor::class, 2)->create();

        $model = new Tutor();
        $expected = $model->pluck('tut_nome', 'tut_id');
        $fromRepository = $this->repo->lists('tut_id', 'tut_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Moisés',
        ]);

        $tutor = factory(Tutor::class)->create([
            'tut_pes_id' => $pessoa->pes_id
        ]);

        $searchResult = $this->repo->search(array(['tut_pes_id', '=', $tutor->tut_pes_id]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Tutor::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Moisés',
        ]);

        $entry = factory(Tutor::class)->create([
            'tut_pes_id' => $pessoa->pes_id
        ]);

        $expected = [
            'tut_id' => $entry->tut_id,
            'pes_nome' => $entry->pessoa->pes_nome
        ];

        $searchResult = $this->repo->search(array(['tut_pes_id', '=', $entry->tut_pes_id]), ['tut_id']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(Tutor::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Tutor::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Tutor();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Tutor::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Tutor::class, 2)->create();

        $sort = [
            'field' => 'tut_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->tut_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Tutor::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Moisés',
        ]);

        $documento = factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '123456789'
        ]);

        $entry = factory(Tutor::class)->create([
            'tut_pes_id' => $pessoa->pes_id
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => '=',
                'term' => 'Moisés'
            ],
            [
                'field' => 'pes_cpf',
                'type' => '=',
                'term' => '123456789'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Moisés', $response->first()->pes_nome);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Moisés'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Moisés', $response->first()->pes_nome);
    }

    public function testListsTutorPessoa()
    {
        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create();
        $response = factory(Modulos\Academico\Models\Tutor::class)->create();

        $tutorgrupo = new TutorGrupo();
        $TutorRepository = new TutorRepository(new Tutor());

        $tutorgrupo->create(['ttg_tut_id' => $response->tut_id, 'ttg_grp_id' => $grupo->grp_id, 'ttg_tipo_tutoria' => 'presencial', 'ttg_data_inicio' => '10/11/2010', 'ttg_data_fim' => null]);

        $tutores = $TutorRepository->listsTutorPessoa($grupo->grp_id);

        $this->assertEmpty($tutores, '');
    }

    public function testFindAllByGrupo()
    {
        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create();
        $response = factory(Modulos\Academico\Models\Tutor::class)->create();

        $tutorgrupo = new TutorGrupo();
        $TutorRepository = new TutorRepository(new Tutor());

        $tutorgrupo->create(['ttg_tut_id' => $response->tut_id, 'ttg_grp_id' => $grupo->grp_id, 'ttg_tipo_tutoria' => 'presencial', 'ttg_data_inicio' => '10/11/2010', 'ttg_data_fim' => null]);

        $tutores = $TutorRepository->findAllByGrupo($grupo->grp_id);

        $this->assertNotEmpty($tutores, '');
    }

    public function testFindallbyTurmaTipoTutoria()
    {
        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create();
        $response = factory(Modulos\Academico\Models\Tutor::class)->create();

        $tutorgrupo = new TutorGrupo();
        $TutorRepository = new TutorRepository(new Tutor());

        $tutorgrupo->create(['ttg_tut_id' => $response->tut_id, 'ttg_grp_id' => $grupo->grp_id, 'ttg_tipo_tutoria' => 'presencial', 'ttg_data_inicio' => '10/11/2010', 'ttg_data_fim' => null]);

        $tutores = $TutorRepository->FindallbyTurmaTipoTutoria($grupo->turma->trm_id, 'presencial');
      
        $this->assertNotEmpty($tutores, '');
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
