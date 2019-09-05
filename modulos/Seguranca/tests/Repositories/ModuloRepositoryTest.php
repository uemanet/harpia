<?php

use Tests\ModulosTestCase;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Seguranca\Models\Permissao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;
use Modulos\Seguranca\Repositories\ModuloRepository;

class ModuloRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(ModuloRepository::class);
        $this->table = 'seg_modulos';
    }

    public function testCreate()
    {
        $data = factory(Modulo::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Modulo::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Modulo::class)->create();
        $id = $entry->mod_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Modulo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Modulo::class)->create();
        $id = $entry->mod_id;

        $data = $entry->toArray();

        $data['mod_slug'] = "slugmodule";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Modulo::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Modulo::class)->create();
        $id = $entry->mod_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Modulo::class, 2)->create();

        $model = new Modulo();
        $expected = $model->pluck('mod_slug', 'mod_id');
        $fromRepository = $this->repo->lists('mod_id', 'mod_slug');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Modulo::class, 2)->create();

        factory(Modulo::class)->create([
            'mod_slug' => "slugmodule"
        ]);

        $searchResult = $this->repo->search(array(['mod_slug', '=', "slugmodule"]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Modulo::class, 2)->create();

        $entry = factory(Modulo::class)->create([
            'mod_slug' => "New name"
        ]);

        $expected = [
            'mod_id' => $entry->mod_id,
            'mod_slug' => $entry->mod_slug
        ];

        $searchResult = $this->repo->search(array(['mod_slug', '=', "New name"]), ['mod_id', 'mod_slug']);

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
        $created = factory(Modulo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Modulo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Modulo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulo::class, 2)->create();

        $sort = [
            'field' => 'mod_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mod_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulo::class, 2)->create();
        factory(Modulo::class)->create([
            'mod_slug' => 'slugmodule',
        ]);

        $search = [
            [
                'field' => 'mod_slug',
                'type' => '=',
                'term' => 'slugmodule'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('slugmodule', $response->first()->mod_slug);
    }

    public function testPaginateRequest()
    {
        factory(Modulo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mod_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetByUser()
    {
        $usuario = factory(Usuario::class)->create();
        $userId = $usuario->usr_id;

        // Modulos
        factory(Modulo::class, 2)->create();
        $nomeModulo = str_random(7);
        $modulo = factory(Modulo::class)->create([
            'mod_nome' => $nomeModulo,
            'mod_slug' => strtolower($nomeModulo),
        ]);

        // Perfis
        factory(Perfil::class, 2)->create([
            'prf_mod_id' => random_int(1, 2)
        ]);

        $perfil = factory(Perfil::class)->create([
            'prf_mod_id' => $modulo->mod_id
        ]);

        $permissoes = [];

        $permissoes[] = factory(Permissao::class)->create([
            'prm_rota' => strtolower($nomeModulo) . ".index.index"
        ])->prm_id;

        for ($i = 0; $i < 10; $i++) {
            $permissoes[] = factory(Permissao::class)->create([
                'prm_rota' => strtolower($nomeModulo) . "." . str_random(5)
            ])->prm_id;
        }

        // Sincronizar perfil com permissoes
        $perfil->permissoes()->sync($permissoes);

        // Sincronizar perfil para usuario
        $usuario->perfis()->sync($perfil->prf_id);

        // Mockup login
        $this->be($usuario);

        //Gera estrutura do menu em cache
        $seguranca = $this->app[Seguranca::class];
        $seguranca->makeCachePermissoes();
        $seguranca->makeCacheMenu();

        // Menu
        $result = $this->repo->getByUser($userId, true);

        $this->assertEquals(1, $result->count());
        $this->assertEquals($modulo->mod_slug, $result->first()->mod_slug);

        /*
         * Sem menu
         *
         * Os modulos sao deduzidos a partir do cache feito no login
         * Em qualquer caso, os modulos retornados devem ser os mesmos
         */
        $result = $this->repo->getByUser($userId);

        $this->assertEquals(1, $result->count());
        $this->assertEquals($modulo->mod_slug, $result->first()->mod_slug);
    }
}
