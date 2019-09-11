<?php

use Tests\ModulosTestCase;
use Illuminate\Support\Str;
use Tests\Helpers\RouteResolver;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Modulo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Seguranca\Providers\ActionButton\ActionButton;

class ActionButtonTest extends ModulosTestCase
{
    private function mockGridSelect()
    {
        $permissionGet = Permissao::find(1);
        $permissionPost = Permissao::find(random_int(2, 9));

        $config = [
            'type' => 'SELECT',
            'config' => [
                'classButton' => 'btn-default',
                'label' => 'Selecione'
            ],
            'buttons' => [
                [
                    'classButton' => '',
                    'icon' => 'fa fa-pencil',
                    'route' => $permissionGet->prm_rota,
                    'parameters' => ['id' => random_int(1, 100)],
                    'label' => 'Editar',
                    'id' => 'btnIdentifier',
                    'method' => 'get'
                ],
                [
                    'classButton' => 'btn-delete text-red',
                    'icon' => 'fa fa-trash',
                    'route' => $permissionPost->prm_rota,
                    'id' => random_int(1, 100),
                    'label' => 'Excluir',
                    'method' => 'post'
                ]
            ]
        ];

        return $config;
    }

    private function mockGridLine()
    {
        $permissionGet = Permissao::find(1);
        $permissionPost = Permissao::find(random_int(2, 9));

        $config = [
            'type' => 'LINE',
            'buttons' => [
                [
                    'classButton' => '',
                    'icon' => 'fa fa-pencil',
                    'route' => $permissionGet->prm_rota,
                    'parameters' => ['id' => random_int(1, 100)],
                    'label' => 'Editar',
                    'attributes' => [
                        'onClick' => 'anyFunction()'
                    ],
                    'method' => 'get'
                ],
                [
                    'classButton' => 'btn-delete text-red',
                    'icon' => 'fa fa-trash',
                    'route' => $permissionPost->prm_rota,
                    'id' => random_int(1, 100),
                    'label' => 'Excluir',
                    'method' => 'post'
                ]
            ]
        ];

        return $config;
    }

    private function mockGridButtons()
    {
        $permissionGet = Permissao::find(1);
        $permissionPost = Permissao::find(random_int(2, 9));

        $config = [
            'type' => 'BUTTONS',
            'config' => [
                'classButton' => 'btn-default',
                'label' => 'Selecione',
                'showLabel' => true
            ],
            'buttons' => [
                [
                    'classButton' => '',
                    'icon' => 'fa fa-pencil',
                    'route' => $permissionGet->prm_rota,
                    'parameters' => ['id' => random_int(1, 100)],
                    'label' => 'Editar',
                    'method' => 'get'
                ],
                [
                    'classButton' => 'btn-delete text-red',
                    'icon' => 'fa fa-trash',
                    'route' => $permissionPost->prm_rota,
                    'id' => random_int(1, 100),
                    'label' => 'Excluir',
                    'method' => 'post'
                ]
            ]
        ];

        return $config;
    }

    private function mockTButtons()
    {
        $permissionGet = Permissao::find(1);
        $permissionPost = Permissao::find(random_int(2, 9));

        $buttons = [];

        $btnGet = new TButton();
        $btnGet->setRoute($permissionGet->prm_rota)->setParameters(['id' => $permissionGet->prm_id]);
        $buttons[] = $btnGet;

        $btnPost = new TButton();
        $btnPost->setRoute($permissionPost->prm_rota)->setParameters(['id' => $permissionPost->prm_id]);
        $buttons[] = $btnPost;

        return $buttons;
    }

    private function mockPermissoes()
    {
        $action = function () {
            return "Passed!";
        };

        /*
         * Mockar rotas fake
         */
        $routeCollection = $this->app['router']->getRoutes();

        $usuario = factory(Usuario::class)->create();
        /*
         * Mockup da estrutura de permissoes do sistema
         */

        // Modulos
        $nomeModulo = str_replace(" ", "", Str::random(7));
        factory(Modulo::class)->create([
            'mod_nome' => $nomeModulo,
            'mod_slug' => strtolower($nomeModulo)
        ]);

        $nomeModulo = str_replace(" ", "", Str::random(7));
        factory(Modulo::class)->create([
            'mod_nome' => $nomeModulo,
            'mod_slug' => strtolower($nomeModulo)
        ]);

        for ($i = 1; $i <= 2; $i++) {
            $modulo = Modulo::find($i);
            $moduloId = $modulo->mod_id;
            $nomeModulo = $modulo->mod_nome;

            // Perfis
            factory(Perfil::class, 2)->create([
                'prf_mod_id' => $moduloId
            ]);

            $perfil = factory(Perfil::class)->create([
                'prf_mod_id' => $moduloId
            ]);

            // Permissoes
            $permissoes = [];

            $permissoes[] = factory(Permissao::class)->create([
                'prm_rota' => strtolower($nomeModulo) . ".index.index"
            ])->prm_id;

            $routeName = strtolower($nomeModulo . ".index.index");
            $routeUri = implode("/", explode(".", $routeName));

            $routeCollection->add($this->app['router']->match(['GET', 'POST'], $routeUri, $action)->name($routeName)->middleware('web', 'auth'));

            $this->app['router']->match(['GET', 'POST'], $routeUri, $action)->name($routeName)->middleware('web', 'auth');

            for ($i = 0; $i < 10; $i++) {
                $routeName = strtolower($nomeModulo . "." . str_replace(" ", "", Str::random(5)));

                $permissoes[] = factory(Permissao::class)->create([
                    'prm_rota' => $routeName
                ])->prm_id;

                $routeUri = implode("/", explode(".", $routeName));

                $routeCollection->add($this->app['router']->match(['GET', 'POST'], $routeUri, $action)->name($routeName)->middleware('web', 'auth'));
            }

            // Itens de Menu
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
                'mit_item_pai' => $subCategoria->mit_id,
                'mit_rota' => Permissao::find(random_int(1, 9))->prm_rota
            ]);

            $subItem = factory(MenuItem::class)->create([
                'mit_mod_id' => $moduloId,
                'mit_item_pai' => $subCategoria->mit_id,
                'mit_rota' => Permissao::find(10)->prm_rota
            ]);

            // Sincronizar perfil com permissoes
            $perfil->permissoes()->sync($permissoes);

            // Sincronizar perfil para usuario
            $usuario->perfis()->sync($perfil->prf_id);
        }

        $this->app['router']->setRoutes($routeCollection);
    }

    public function testRender()
    {
        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        $buttons = $this->mockTButtons();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Individual
        $renderized = $actionButton->render($buttons[0]);
        $this->assertEquals("", $renderized);

        # Array de Buttons
        $renderized = $actionButton->render($buttons);
        $this->assertTrue(is_string($actionButton->render($buttons)));
        $this->assertStringStartsWith("<a href=", $renderized);
    }

    public function testGridSelect()
    {
        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        $buttons = $this->mockGridSelect();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<div", $renderized);
        $this->assertTrue(strlen(strstr($renderized, "dropdown-menu")) > 0);
    }

    public function testGridButtons()
    {
        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        # Com 'showLabel'
        $buttons = $this->mockGridButtons();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<a style=", $renderized);

        # Sem 'showLabel'
        $buttons = $this->mockGridButtons();
        $buttons['config']['showLabel'] = false;

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<a style=", $renderized);
    }

    public function testGridLine()
    {
        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        $buttons = $this->mockGridLine();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<table>", $renderized);
    }

    public function testRenderWithSecurity()
    {
        // Seguranca habilitada
        putenv("IS_SECURITY_ENNABLED=TRUE");

        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        $buttons = $this->mockTButtons();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Individual
        $renderized = $actionButton->render($buttons[0]);
        $this->assertEquals("", $renderized);

        # Array de Buttons
        $renderized = $actionButton->render($buttons);
        $this->assertTrue(is_string($actionButton->render($buttons)));
        $this->assertStringStartsWith("<a href=", $renderized);
    }

    public function testGridSelectWithSecurity()
    {
        // Seguranca habilitada
        putenv("IS_SECURITY_ENNABLED=TRUE");

        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        $buttons = $this->mockGridSelect();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<div", $renderized);
        $this->assertTrue(strlen(strstr($renderized, "dropdown-menu")) > 0);
    }

    public function testGridButtonsWithSecurity()
    {
        // Seguranca habilitada
        putenv("IS_SECURITY_ENNABLED=TRUE");

        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        # Com 'showLabel'
        $buttons = $this->mockGridButtons();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<a style=", $renderized);

        # Sem 'showLabel'
        $buttons = $this->mockGridButtons();
        $buttons['config']['showLabel'] = false;

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<a style=", $renderized);
    }

    public function testGridLineWithSecurity()
    {
        // Seguranca habilitada
        putenv("IS_SECURITY_ENNABLED=TRUE");

        $this->mockPermissoes();
        $usuario = Usuario::all()->first();
        $userId = $usuario->usr_id;

        /*
         * Login
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertNotNull(Cache::get('MENU_' . $userId));
        $this->assertNotNull(Cache::get('PERMISSOES_' . $userId));

        $buttons = $this->mockGridLine();

        $this->app['request']->setRouteResolver(function () use ($buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

        # Array de Buttons
        $renderized = $actionButton->grid($buttons);
        $this->assertStringStartsWith("<table>", $renderized);
    }
}
