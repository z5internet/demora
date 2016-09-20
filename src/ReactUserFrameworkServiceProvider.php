<?php namespace darrenmerrett\ReactUserFramework;

use Illuminate\Support\ServiceProvider;

use Illuminate\Contracts\Http\Kernel;

use App;

class ReactUserFrameworkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {

        $this->publishes([
    
            __DIR__.'/resources/assets/react-app/' => base_path('resources/assets/react-app/'),
        
            $this->configPath() => config_path('DM/react-user-framework.php'),

            __DIR__.'/resources/views/' => base_path('resources/views/vendor/ruf/'),
                
        ]);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'react-user-framework');     

        if (! $this->app->routesAreCached()) {
            
            require __DIR__.'/app/Http/routes.php';
            
        }

        if ($kernel->hasMiddleware("App\Http\Middleware\EncryptCookies")) {
            
            abort(500,"App\Http\Middleware\EncryptCookies is a registered Middleware that needs to be removed.");

        }
        
        $kernel->pushMiddleware('darrenmerrett\ReactUserFramework\App\Http\Middleware\EncryptCookies');
        
        $kernel->pushMiddleware('Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse');
        
        $kernel->pushMiddleware('Illuminate\Session\Middleware\StartSession');

        $kernel->pushMiddleware('Barryvdh\Cors\HandleCors');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom($this->configPath(), 'DM/react-user-framework');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('userController', 'darrenmerrett\ReactUserFramework\facades\userControllerFacade' );

        $loader->alias('User', 'App\User');

        $this->commands([

            \darrenmerrett\ReactUserFramework\app\Console\Commands\build::class,
            \darrenmerrett\ReactUserFramework\app\Console\Commands\server::class,
            \darrenmerrett\ReactUserFramework\app\Console\Commands\install::class,

        ]);

        App::register(\Barryvdh\Cors\ServiceProvider::class);

        App::register(\Intervention\Image\ImageServiceProvider::class);

        $loader->alias('Image', 'Intervention\Image\Facades\Image');

    }

    protected function configPath()
    {
        
        return __DIR__.'/installationFiles/config/react-user-framework.php';
        
    }
    
}
