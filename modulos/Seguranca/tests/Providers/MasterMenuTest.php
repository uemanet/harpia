<?php

use Illuminate\View\View;
use Tests\ModulosTestCase;
use Harpia\Mock\RouteResolver;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Modulo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class MasterMenuTest extends ModulosTestCase
{
    private function mockPermissoes()
    {
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

            for ($i = 0; $i < 10; $i++) {
                $routeName = strtolower($nomeModulo) . "." . str_replace(" ", "", str_random(5));

                $permissoes[] = factory(Permissao::class)->create([
                    'prm_rota' => $routeName
                ])->prm_id;

                $routeUri = implode("/", explode(".", $routeName));

                Route::get($routeUri, function () {
                    return "Passed!";
                })->name($routeName);
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
                'mit_rota' => Permissao::find(random_int(1, 10))->prm_rota
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

        $this->app['request']->setRouteResolver(function () use ($userId) {
            $resolver = new RouteResolver("");
            $resolver->setPermissions(Cache::get('PERMISSOES_' . $userId));
            return $resolver;
        });

        $result = $this->app['MasterMenu']->render();

        $this->assertInstanceOf(View::class, $result);
    }
}
