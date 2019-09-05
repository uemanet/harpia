<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Models\Documento;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Usuario;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Seguranca\Repositories\UsuarioRepository;

class UsuarioRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(UsuarioRepository::class);
        $this->table = 'seg_usuarios';
    }

    public function testCreate()
    {
        $data = factory(Usuario::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Usuario::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Usuario::class)->create();
        $id = $entry->usr_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Usuario::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Usuario::class)->create();
        $id = $entry->usr_id;

        $data = $entry->toArray();

        $data['usr_usuario'] = "username@unit.com";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Usuario::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Usuario::class)->create();
        $id = $entry->usr_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Usuario::class, 2)->create();

        $model = new Usuario();
        $expected = $model->pluck('usr_usuario', 'usr_id');
        $fromRepository = $this->repo->lists('usr_id', 'usr_usuario');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Usuario::class, 2)->create();

        factory(Usuario::class)->create([
            'usr_usuario' => "username@unit.com"
        ]);

        $searchResult = $this->repo->search(array(['usr_usuario', '=', "username@unit.com"]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Usuario::class, 2)->create();

        $entry = factory(Usuario::class)->create([
            'usr_usuario' => "New name"
        ]);

        $expected = [
            'usr_id' => $entry->usr_id,
            'usr_usuario' => $entry->usr_usuario
        ];

        $searchResult = $this->repo->search(array(['usr_usuario', '=', "New name"]), ['usr_id', 'usr_usuario']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
        $this->assertEquals($expected, $searchResult->first()->toArray());
    }

    public function testPaginateWithSearchForCpf()
    {
        factory(Usuario::class, 2)->create();
        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'fulano de tal',
        ]);

        $usuario = factory(Usuario::class)->create([
            'usr_pes_id' => $pessoa->pes_id
        ]);

        factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '12345678965'
        ]);

        $search = [
            [
                'field' => 'pes_cpf',
                'type' => '=',
                'term' => '12345678965'
            ],
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'fulano de tal'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('fulano de tal', $response->first()->pes_nome);
    }


    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(Usuario::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Usuario::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Usuario();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Usuario::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Usuario::class, 2)->create();

        $sort = [
            'field' => 'usr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->usr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Usuario::class, 2)->create();
        factory(Usuario::class)->create([
            'usr_usuario' => 1,
        ]);

        $search = [
            [
                'field' => 'usr_usuario',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals(1, $response->first()->usr_usuario);
    }

    public function testPaginateRequest()
    {
        factory(Usuario::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'usr_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testSincronizarPerfis()
    {
        $usuario = factory(Usuario::class)->create();
        $id = $usuario->usr_id;

        $before = $usuario->perfis;

        $this->assertEquals(0, $before->count());

        $perfis = factory(Perfil::class, 3)->create()->pluck('prf_id')->toArray();

        // Sincroniza os perfis
        $this->repo->sincronizarPerfis($id, $perfis);
        $usuario = Usuario::find($id);

        $after = $usuario->perfis;
        $toMatch = $after->pluck('prf_id')->toArray();

        $this->assertEquals($perfis, $toMatch);
        $this->assertEquals(3, $after->count());
    }
}
