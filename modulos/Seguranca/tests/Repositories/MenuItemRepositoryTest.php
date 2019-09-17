<?php

use Tests\ModulosTestCase;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\MenuItem;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Seguranca\Repositories\MenuItemRepository;

class MenuItemRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(MenuItemRepository::class);
        $this->table = 'seg_menu_itens';
    }

    public function testCreate()
    {
        $data = factory(MenuItem::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(MenuItem::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(MenuItem::class)->create();
        $id = $entry->mit_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(MenuItem::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(MenuItem::class)->create();
        $id = $entry->mit_id;

        $data = $entry->toArray();

        $data['mit_nome'] = "menuitem";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(MenuItem::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(MenuItem::class)->create();
        $id = $entry->mit_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(MenuItem::class, 2)->create();

        $model = new MenuItem();
        $expected = $model->pluck('mit_nome', 'mit_id');
        $fromRepository = $this->repo->lists('mit_id', 'mit_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(MenuItem::class, 2)->create();

        factory(MenuItem::class)->create([
            'mit_nome' => "menuitem"
        ]);

        $searchResult = $this->repo->search(array(['mit_nome', '=', "menuitem"]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(MenuItem::class, 2)->create();

        $entry = factory(MenuItem::class)->create([
            'mit_nome' => "New name"
        ]);

        $expected = [
            'mit_id' => $entry->mit_id,
            'mit_nome' => $entry->mit_nome
        ];

        $searchResult = $this->repo->search(array(['mit_nome', '=', "New name"]), ['mit_id', 'mit_nome']);

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
        $created = factory(MenuItem::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(MenuItem::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new MenuItem();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(MenuItem::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(MenuItem::class, 2)->create();

        $sort = [
            'field' => 'mit_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mit_id);
    }

    public function testPaginateWithSearch()
    {
        factory(MenuItem::class, 2)->create();
        factory(MenuItem::class)->create([
            'mit_nome' => 'menuitem',
        ]);

        $search = [
            [
                'field' => 'mit_nome',
                'type' => '=',
                'term' => 'menuitem'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('menuitem', $response->first()->mit_nome);
    }

    public function testPaginateRequest()
    {
        factory(MenuItem::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mit_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetCategorias()
    {
        $modulo = factory(Modulo::class)->create();
        $moduloId = $modulo->mod_id;

        // Mock de items
        $categorias = factory(MenuItem::class, 2)->create([
            'mit_mod_id' => $moduloId
        ]);

        $subItens = factory(MenuItem::class, 5)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => random_int(1, 2)
        ]);

        $fromRepository = $this->repo->getCategorias($moduloId);

        $expected = $categorias->pluck('mit_nome', 'mit_id')->toArray();
        $toMatch = $fromRepository->pluck('mit_nome', 'mit_id')->toArray();

        $this->assertEquals($categorias->count(), $fromRepository->count());
        $this->assertEquals($expected, $toMatch);
    }

    public function testGetItensFilhos()
    {
        $modulo = factory(Modulo::class)->create();
        $moduloId = $modulo->mod_id;

        // Mock de items
        $categoria = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId
        ]);
        $categoriaId = $categoria->mit_id;

        // Filhos
        $subItens = factory(MenuItem::class, 5)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => $categoriaId
        ]);

        $fromRepository = $this->repo->getItensFilhos($moduloId, $categoriaId);

        $expected = $subItens->pluck('mit_nome', 'mit_id')->toArray();
        $toMatch = $fromRepository->pluck('mit_nome', 'mit_id')->toArray();

        $this->assertEquals($subItens->count(), $fromRepository->count());
        $this->assertEquals($expected, $toMatch);
    }

    public function testIsCategoria()
    {
        $modulo = factory(Modulo::class)->create();
        $moduloId = $modulo->mod_id;

        // Mock de items
        $categoria = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_rota' => null
        ]);

        $categoriaInvalida = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_rota' => "any.given.route"
        ]);

        $categoriaId = $categoria->mit_id;

        // Filho
        $subItem = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => $categoriaId
        ]);

        $this->assertTrue($this->repo->isCategoria($categoria->mit_id));
        $this->assertFalse($this->repo->isCategoria($categoriaInvalida->mit_id));
        $this->assertFalse($this->repo->isCategoria($subItem->mit_id));
    }

    public function testIsSubCategoria()
    {
        $modulo = factory(Modulo::class)->create();
        $moduloId = $modulo->mod_id;

        // Mock de items
        $categoria = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_rota' => null
        ]);

        $categoriaId = $categoria->mit_id;

        // Filho
        $subCategoria = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => $categoriaId,
            'mit_rota' => null
        ]);

        $subItem = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => $subCategoria->mit_id
        ]);

        $this->assertFalse($this->repo->isSubCategoria($categoria->mit_id));
        $this->assertFalse($this->repo->isSubCategoria($subItem->mit_id));
        $this->assertTrue($this->repo->isSubCategoria($subCategoria->mit_id));
    }

    public function testIsItem()
    {
        $modulo = factory(Modulo::class)->create();
        $moduloId = $modulo->mod_id;

        // Mock de items
        $categoria = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_rota' => null
        ]);

        $categoriaId = $categoria->mit_id;

        // Filho
        $subCategoria = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => $categoriaId,
            'mit_rota' => null
        ]);

        $subItem = factory(MenuItem::class)->create([
            'mit_mod_id' => $moduloId,
            'mit_item_pai' => $subCategoria->mit_id
        ]);

        $this->assertFalse($this->repo->isItem($categoria->mit_id));
        $this->assertFalse($this->repo->isItem($subCategoria->mit_id));
        $this->assertTrue($this->repo->isItem($subItem->mit_id));
    }
}
