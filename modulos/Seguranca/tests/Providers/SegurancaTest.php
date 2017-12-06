<?php

use Harpia\Menu\MenuTree;
use Tests\ModulosTestCase;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Usuario;
use Illuminate\Support\Facades\Cache;
use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class SegurancaTest extends ModulosTestCase
{
    public function testGetUser()
    {
        $usuario = factory(Usuario::class)->create();
        $userId = $usuario->usr_id;

        /*
         * Mockup da estrutura de permissoes do sistema
         */

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

        /*
         * Login
         */
        $this->actingAs($usuario);
        $user = $this->app[Seguranca::class]->getUser();

        $this->assertInstanceOf(Usuario::class, $user);
        $this->assertEquals($usuario->usr_id, $user->usr_id);
    }

    public function testMakeCacheMenu()
    {
        $usuario = factory(Usuario::class)->create();
        $userId = $usuario->usr_id;

        /*
         * Mockup da estrutura de permissoes do sistema
         */

        // Modulos
        factory(Modulo::class, 2)->create();

        for ($i = 0; $i < 3; $i++) {
            $nomeModulo = str_random(7);

            $modulo = factory(Modulo::class)->create([
                'mod_nome' => $nomeModulo,
                'mod_slug' => strtolower($nomeModulo),
            ]);
            $moduloId = $modulo->mod_id;

            // Perfis
            factory(Perfil::class, 2)->create([
                'prf_mod_id' => random_int(1, 2)
            ]);

            $perfil = factory(Perfil::class)->create([
                'prf_mod_id' => $modulo->mod_id
            ]);

            // Permissoes
            $permissoes = [];

            $permissoes[] = factory(Permissao::class)->create([
                'prm_rota' => strtolower($nomeModulo) . ".index.index"
            ])->prm_id;

            for ($i = 0; $i < 10; $i++) {
                $permissoes[] = factory(Permissao::class)->create([
                    'prm_rota' => strtolower($nomeModulo) . "." . str_random(5)
                ])->prm_id;
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
                'mit_item_pai' => $subCategoria->mit_id
            ]);

            // Sincronizar perfil com permissoes
            $perfil->permissoes()->sync($permissoes);

            // Sincronizar perfil para usuario
            $usuario->perfis()->sync($perfil->prf_id);
        }

        /*
         * Login
         */
        $this->actingAs($usuario);

        $this->assertNull(Cache::get('MENU_' . $userId));

        $this->app[Seguranca::class]->makeCachePermissoes();
        $this->app[Seguranca::class]->makeCacheMenu();

        $this->assertFalse(is_null(Cache::get('MENU_' . $userId)));
        $this->assertTrue(is_array(Cache::get('MENU_' . $userId)));
        $data = Cache::get('MENU_' . $userId);
        $this->assertInstanceOf(MenuTree::class, array_pop($data));
    }

    public function testMakeCachePermissoes()
    {
        $usuario = factory(Usuario::class)->create();
        $userId = $usuario->usr_id;

        /*
         * Mockup da estrutura de permissoes do sistema
         */

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

        /*
         * Login
         */
        $this->actingAs($usuario);

        $this->assertNull(Cache::get('PERMISSOES_' . $userId));

        $this->app[Seguranca::class]->makeCachePermissoes();

        $this->assertFalse(is_null(Cache::get('PERMISSOES_' . $userId)));
    }

    public function testHasPermission()
    {
        $usuario = factory(Usuario::class)->create();
        $userId = $usuario->usr_id;

        /*
         * Mockup da estrutura de permissoes do sistema
         */

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

        // Uma rota nao eh atribuida para o perfil
        $deniedId = array_pop($permissoes);

        $denied = Permissao::find($deniedId);
        $granted = Permissao::find(random_int(2, 9));

        // Sincronizar perfil com permissoes
        $perfil->permissoes()->sync($permissoes);

        // Sincronizar perfil para usuario
        $usuario->perfis()->sync($perfil->prf_id);

        /*
         * Login e cache de permissoes
         */
        $this->actingAs($usuario);
        $this->app[Seguranca::class]->makeCachePermissoes();

        $this->assertTrue($this->app[Seguranca::class]->haspermission($granted->prm_rota));
        $this->assertFalse($this->app[Seguranca::class]->haspermission($denied->prm_rota));
    }

    public function testHasPermissionPreLoginOpenRoutes()
    {
        /*
         * Usuario Guest
         */
        $preLoginRoutes = config('seguranca.prelogin_openroutes');
        $this->assertTrue($this->app[Seguranca::class]->haspermission($preLoginRoutes[random_int(0, 1)]));
    }

    public function testHasPermissionPostLoginOpenRoutes()
    {
        $usuario = factory(Usuario::class)->create();
        $this->actingAs($usuario);

        /*
         * Rotas abertas ao usuarios logados
         */
        $postLoginRoutes = config('seguranca.postlogin_openroutes');
        $this->assertTrue($this->app[Seguranca::class]->haspermission($postLoginRoutes[random_int(0, 4)]));
    }

    public function testHasPermissionAsyncRoutes()
    {
        /*
         * Rota negada para usuarios nao autenticados
         */
        $this->assertFalse($this->app[Seguranca::class]->haspermission("async.some.route"));

        /*
         * Rota permitida para usuarios autenticados
         */
        $usuario = factory(Usuario::class)->create();
        $this->actingAs($usuario);

        $this->assertTrue($this->app[Seguranca::class]->haspermission("some.async.route"));
    }
}
