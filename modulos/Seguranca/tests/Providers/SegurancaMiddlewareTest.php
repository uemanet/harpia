<?php

use Illuminate\Http\Request;
use Tests\Helpers\RouteResolver;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Usuario;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Modulos\Seguranca\Models\Permissao;
use Illuminate\Support\Facades\Artisan;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;
use Modulos\Seguranca\Providers\Seguranca\SegurancaMiddleware;

class SegurancaMiddlewareTest extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');
        $app = require dirname(__DIR__) . DIRECTORY_SEPARATOR . '../../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('modulos:migrate');
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function testHandleSecurityEnabled()
    {
        // Seguranca habilitada
        putenv("IS_SECURITY_ENNABLED=TRUE");
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
        $routeName = strtolower($nomeModulo) . ".index.index";

        $permissoes[] = factory(Permissao::class)->create([
            'prm_rota' => $routeName
        ])->prm_id;

        $routeUri = implode("/", explode(".", strtolower($nomeModulo) . ".index"));

        Route::get($routeUri, function () {
            return "Passed!";
        })->name($routeName);

        for ($i = 0; $i < 10; $i++) {
            $routeName = strtolower($nomeModulo) . "." . str_random(5);

            $permissoes[] = factory(Permissao::class)->create([
                'prm_rota' => $routeName
            ])->prm_id;

            $routeUri = implode("/", explode(".", $routeName));

            Route::get($routeUri, function () {
                return "Passed!";
            })->name($routeName);
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

        $this->assertFalse(is_null(Cache::get('PERMISSOES_' . $userId)));
        $middleware = $this->app->make(SegurancaMiddleware::class);

        // Deve barrar uma requisicao para uma rota para a qual o usuario nao tem permissao
        $routeUri = implode("/", explode(".", $denied->prm_rota));
        $request = Request::create($this->baseUrl . "/" . $routeUri, "GET");

        $request->setRouteResolver(function () use ($denied) {
            return new RouteResolver($denied->prm_rota);
        });

        $response = $middleware->handle($request, function (Request $request) {
            return "You shall not pass!";
        });

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());

        // Deve deixar passar uma requisicao para uma rota para a qual o usuario tem permissao
        $routeUri = implode("/", explode(".", $granted->prm_rota));
        $request = Request::create($this->baseUrl . "/" . $routeUri, "GET");

        $request->setRouteResolver(function () use ($granted) {
            return new RouteResolver($granted->prm_rota);
        });

        $response = $middleware->handle($request, function (Request $request) {
            return "Welcome Sir!";
        });

        $this->assertEquals("Welcome Sir!", $response);
    }

    public function testHandleSecurityDisabled()
    {
        // Seguranca desabilitada
        putenv("IS_SECURITY_ENNABLED=FALSE");
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
        $routeName = strtolower($nomeModulo) . ".index.index";

        $permissoes[] = factory(Permissao::class)->create([
            'prm_rota' => $routeName
        ])->prm_id;

        $routeUri = implode("/", explode(".", strtolower($nomeModulo) . ".index"));

        Route::get($routeUri, function () {
            return "Passed!";
        })->name($routeName);

        for ($i = 0; $i < 10; $i++) {
            $routeName = strtolower($nomeModulo) . "." . str_random(5);

            $permissoes[] = factory(Permissao::class)->create([
                'prm_rota' => $routeName
            ])->prm_id;

            $routeUri = implode("/", explode(".", $routeName));

            Route::get($routeUri, function () {
                return "Passed!";
            })->name($routeName);
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

        $this->assertFalse(is_null(Cache::get('PERMISSOES_' . $userId)));
        $middleware = $this->app->make(SegurancaMiddleware::class);

        // Com seguranca desabilitada, deve deixar passar todas as rotas
        $routeUri = implode("/", explode(".", $denied->prm_rota));
        $request = Request::create($this->baseUrl . "/" . $routeUri, "GET");

        $request->setRouteResolver(function () use ($denied) {
            return new RouteResolver($denied->prm_rota);
        });

        $response = $middleware->handle($request, function (Request $request) {
            return "Security disabled";
        });

        $this->assertEquals("Security disabled", $response);

        // Deve deixar passar uma requisicao para uma rota para a qual o usuario tem permissao
        $routeUri = implode("/", explode(".", $granted->prm_rota));
        $request = Request::create($this->baseUrl . "/" . $routeUri, "GET");

        $request->setRouteResolver(function () use ($granted) {
            return new RouteResolver($granted->prm_rota);
        });

        $response = $middleware->handle($request, function (Request $request) {
            return "Welcome Sir!";
        });

        $this->assertEquals("Welcome Sir!", $response);
    }
}
