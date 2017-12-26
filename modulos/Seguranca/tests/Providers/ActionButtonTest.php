<?php

use Tests\ModulosTestCase;
use Harpia\Mock\RouteResolver;
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
                'label' => 'Selecione'
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

        $usuario = factory(Usuario::class)->create();
        /*
         * Mockup da estrutura de permissoes do sistema
         */

        // Modulos
        $nomeModulo = str_replace(" ", "", str_random(7));
        factory(Modulo::class)->create([
            'mod_nome' => $nomeModulo,
            'mod_slug' => strtolower($nomeModulo)
        ]);

        $nomeModulo = str_replace(" ", "", str_random(7));
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

            $this->app['router']->match(['GET', 'POST'], $routeUri, $action)->name($routeName)->middleware('web', 'auth');

            for ($i = 0; $i < 10; $i++) {
                $routeName = strtolower($nomeModulo . "." . str_replace(" ", "", str_random(5)));

                $permissoes[] = factory(Permissao::class)->create([
                    'prm_rota' => $routeName
                ])->prm_id;

                $routeUri = implode("/", explode(".", $routeName));

                $this->app['router']->match(['GET', 'POST'], $routeUri, $action)->name($routeName)->middleware('web', 'auth');
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

        $this->app['request']->setRouteResolver(function () use ($userId, $buttons) {
            $resolver = new RouteResolver($buttons[0]->getRoute());
            $resolver->setPermissions(Cache::get('PERMISSOES_' . $userId));
            return $resolver;
        });

        $actionButton = new ActionButton($this->app);

//        dd($buttons[0]->getRoute());
        dd($this->app['router']->getRoutes()->hasNamedRoute($buttons[0]->getRoute()));
        dd($actionButton->render($buttons));
    }

    public function testGridSelect()
    {
    }

    public function testGridButtons()
    {
    }

    public function testGridLine()
    {
    }
}
