<?php

namespace Modulos;

use Illuminate\Support\ServiceProvider;
use Route;

class ModulosServiceProvider extends ServiceProvider
{
    /**
     * O método register é o local correto para registrar outros Service Providers
     * antes que a aplicação comece a rodar o boot.
     */
    public function register()
    {
        $modulosPath = base_path('modulos');
        $diretorios = array_filter(glob($modulosPath . '/*'), 'is_dir');

        foreach ($diretorios as $dir) {
            $modulo = basename($dir);

            if (!$this->isModuleActive($dir)) {
                continue;
            }

            // AUTO-DISCOVERY DE PROVIDERS: Se o módulo tiver o seu próprio Provider, injeta no Laravel
            $providerClass = "Modulos\\{$modulo}\\Providers\\{$modulo}ServiceProvider";
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    /**
     * O método boot sobe as rotas, views e migrations dos módulos ativos
     */
    public function boot()
    {
        $modulosPath = base_path('modulos');
        $diretorios = array_filter(glob($modulosPath . '/*'), 'is_dir');

        foreach ($diretorios as $dir) {
            $modulo = basename($dir);

            if (!$this->isModuleActive($dir)) {
                continue;
            }

            // Carrega as Rotas
            if (file_exists($dir . '/routes.php')) {
                Route::group([
                    'middleware' => 'web',
                    'namespace' => "Modulos\\{$modulo}",
                ], function ($router) use ($dir) {
                    require $dir . '/routes.php';
                });
            }

            // Carrega as Views
            if (is_dir($dir . '/Views')) {
                $this->loadViewsFrom($dir . '/Views', $modulo);
            }

            // Carrega as Migrations
            if (is_dir($dir . '/Database/Migrations')) {
                $this->loadMigrationsFrom($dir . '/Database/Migrations');
            }
        }
    }

    /**
     * Verifica se o módulo possui o arquivo module.json e se está ativo
     */
    private function isModuleActive($dir)
    {
        $manifestPath = $dir . '/module.json';

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);

            // Se a chave active existir e for false, o módulo está desativado
            if (isset($manifest['active']) && $manifest['active'] === false) {
                return false;
            }
        }

        // Por padrão, se não tiver o manifesto (módulos antigos), considera ativo
        return true;
    }
}