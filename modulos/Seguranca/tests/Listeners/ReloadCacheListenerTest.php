<?php


use Tests\ModulosTestCase;
use Illuminate\Support\Str;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Usuario;
use Illuminate\Support\Facades\Cache;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Events\ReloadCacheEvent;
use Modulos\Seguranca\Listeners\ReloadCacheListener;

/**
 * Class ReloadCacheListenerTest
 * @group Listeners
 */
class ReloadCacheListenerTest extends ModulosTestCase
{
    public function testHandle()
    {
        $usuario = factory(Usuario::class)->create();
        $userId = $usuario->usr_id;

        /*
         * Mockup da estrutura de permissoes do sistema
         */

        // Modulos
        factory(Modulo::class, 2)->create();
        $nomeModulo = Str::random(7);
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
                'prm_rota' => strtolower($nomeModulo) . "." . Str::random(5)
            ])->prm_id;
        }

        // Sincronizar perfil com permissoes
        $perfil->permissoes()->sync($permissoes);

        // Sincronizar perfil para usuario
        $usuario->perfis()->sync($perfil->prf_id);

        /**
         * Loga usuario sem passar pelo controller de autenticacao.
         * Isso eh feito para evitar que o controller faca o
         * cache das informacoes que devem ser feito pelo listener
         */
        $this->be($usuario);

        $this->assertNull(Cache::get('MENU_' . $userId));
        $this->assertNull(Cache::get('PERMISSOES_' . $userId));

        $evento = new ReloadCacheEvent();
        event($evento);

        $this->assertFalse(is_null(Cache::get('MENU_' . $userId)));
        $this->assertFalse(is_null(Cache::get('PERMISSOES_' . $userId)));
    }
}
