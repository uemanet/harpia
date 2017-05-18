<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class SegurancaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Seguranca::class];
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Seguranca::class, function ($app) {
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
                return "<?php if(app('Modulos\Seguranca\Providers\Seguranca\Seguranca')->haspermission($expression)): ?>";
            });

            $bladeCompiler->directive('endhaspermission', function ($expression) {
                return '<?php endif; ?>';
            });
        });
    }
}
