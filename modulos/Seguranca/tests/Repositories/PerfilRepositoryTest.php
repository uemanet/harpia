<?php

use Tests\ModulosTestCase;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Seguranca\Models\Permissao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Seguranca\Repositories\PerfilRepository;

class PerfilRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(PerfilRepository::class);
        $this->table = 'seg_perfis';
    }

    public function testCreate()
    {
        $data = factory(Perfil::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Perfil::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Perfil::class)->create();
        $id = $entry->prf_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Perfil::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Perfil::class)->create();
        $id = $entry->prf_id;

        $data = $entry->toArray();

        $data['prf_nome'] = "profile";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Perfil::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Perfil::class)->create();
        $id = $entry->prf_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Perfil::class, 2)->create();

        $model = new Perfil();
        $expected = $model->pluck('prf_nome', 'prf_id');
        $fromRepository = $this->repo->lists('prf_id', 'prf_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Perfil::class, 2)->create();

        factory(Perfil::class)->create([
            'prf_nome' => "profile"
        ]);

        $searchResult = $this->repo->search(array(['prf_nome', '=', "profile"]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Perfil::class, 2)->create();

        $entry = factory(Perfil::class)->create([
            'prf_nome' => "New name"
        ]);

        $expected = [
            'prf_id' => $entry->prf_id,
            'prf_nome' => $entry->prf_nome
        ];

        $searchResult = $this->repo->search(array(['prf_nome', '=', "New name"]), ['prf_id', 'prf_nome']);

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
        $created = factory(Perfil::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Perfil::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Perfil();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Perfil::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Perfil::class, 2)->create();

        $sort = [
            'field' => 'prf_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->prf_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Perfil::class, 2)->create();
        factory(Perfil::class)->create([
            'prf_nome' => 'permission',
        ]);

        $search = [
            [
                'field' => 'prf_nome',
                'type' => '=',
                'term' => 'permission'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('permission', $response->first()->prf_nome);
    }

    public function testPaginateRequest()
    {
        factory(Perfil::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'prf_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetPerfilModulo()
    {
        $usuario = factory(Usuario::class)->create();
        factory(Modulo::class, 5)->create();

        $nomeModulo = str_random(7);
        $modulo = factory(Modulo::class)->create([
            'mod_nome' => $nomeModulo,
            'mod_slug' => strtolower($nomeModulo),
        ]);

        $perfil = factory(Perfil::class)->create([
            'prf_mod_id' => $modulo->mod_id
        ]);

        $permissoes = [];

        for ($i = 0; $i < 10; $i++) {
            $permissoes[] = factory(Permissao::class)->create([
                'prm_rota' => $nomeModulo . "." . str_random(5)
            ]);
        }

        $result = $this->repo->getPerfilModulo($perfil);
        $result = array_pop($result);

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('permissoes', $result);
        $this->assertEquals(10, count($result['permissoes']));
    }

    public function testSincronizarPermissoes()
    {
        $perfil = factory(Perfil::class)->create();
        $id = $perfil->prf_id;

        $before = $perfil->permissoes;

        $this->assertEquals(0, $before->count());

        $permissoes = factory(Permissao::class, 3)->create()->pluck('prm_id')->toArray();

        // Sincroniza os perfis
        $this->repo->sincronizarPermissoes($id, $permissoes);
        $perfil = Perfil::find($id);

        $after = $perfil->permissoes;
        $toMatch = $after->pluck('prm_id')->toArray();

        $this->assertEquals($permissoes, $toMatch);
        $this->assertEquals(3, $after->count());
    }

    public function testGetAllByModulo()
    {
        $modulo = factory(Modulo::class)->create();
        $id = $modulo->mod_id;

        $perfis = factory(Perfil::class, 2)->create([
            'prf_mod_id' => $id
        ]);

        $result = $this->repo->getAllByModulo($id);

        $this->assertInstanceOf(Perfil::class, $result->first());
        $this->assertEquals($perfis->count(), $result->count());
    }

    public function testGetModulosWithoutPerfis()
    {
        $usuario = factory(Usuario::class)->create();
        factory(Modulo::class, 2)->create();

        // Modulo sem perfil
        $modulo = factory(Modulo::class)->create();
        $id = $modulo->mod_id;

        $perfilA = factory(Perfil::class)->create([
            'prf_mod_id' => 1
        ]);

        $perfilB = factory(Perfil::class)->create([
            'prf_mod_id' => 2
        ]);

        $usuario->perfis()->sync([$perfilA->prf_id, $perfilB->prf_id]);

        $result = $this->repo->getModulosWithoutPerfis($usuario->usr_id);

        $expected = [
            $modulo->mod_id => $modulo->mod_nome
        ];

        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        $this->assertEquals($expected, $result);
    }

    public function testVerifyExistsPerfilModulo()
    {
        $usuario = factory(Usuario::class)->create();
        $id = $usuario->usr_id;

        factory(Modulo::class, 2)->create();

        $atribuido = factory(Perfil::class)->create([
            'prf_mod_id' => 1
        ]);

        $naoAtribuido = factory(Perfil::class)->create([
            'prf_mod_id' => 2
        ]);

        $usuario->perfis()->sync([$atribuido->prf_id]);

        $this->assertTrue($this->repo->verifyExistsPerfilModulo(1, $id));
        $this->assertFalse($this->repo->verifyExistsPerfilModulo(2, $id));
    }
}
