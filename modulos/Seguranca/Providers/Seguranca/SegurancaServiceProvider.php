<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class SegurancaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('seguranca', function ($app) {
            return new Seguranca($app);
        });

        $this->registerBladeExtensions();
    }

    /**
     * Register new blade extensions.
     */
    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            /*
             * add @haspermission and @haspermission to blade compiler
             */
            $bladeCompiler->directive('haspermission', function ($expression) {
                return "<?php if(app('seguranca')->haspermission($expression)): ?>";
            });

            $bladeCompiler->directive('endhaspermission', function ($expression) {
                return '<?php endif; ?>';
            });
        });
    }
}