<?php namespace z5internet\ReactUserFramework\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Contracts\Http\Kernel;

use App;

use Illuminate\Support\Facades\Schema;

use Illuminate\Console\Scheduling\Schedule;

use z5internet\ReactUserFramework\addOns;

use z5internet\ReactUserFramework\App\Http\Controllers\Auth\RUFGuard;

class ReactUserFrameworkLaravelServiceProvider extends ServiceProvider
{

    use ServiceProviderHelpers;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {

        Schema::defaultStringLength(191);

        $this->publishes([

            __DIR__.'/resources/assets/react-app/' => base_path('resources/assets/react-app/'),

            $this->configPath() => config_path('react-user-framework.php'),

            __DIR__.'/resources/views/' => base_path('resources/views/vendor/ruf/'),

        ]);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'react-user-framework');

        $this->addRoutes();

        $kernel->pushMiddleware('z5internet\ReactUserFramework\App\Http\Middleware\AddRufParameterToJSONOutput');

        if (array_get($_SERVER, 'HTTP_ORIGIN')) {

            $kernel->pushMiddleware(\z5internet\ReactUserFramework\App\Http\Middleware\CorsMiddleware::class);

        }

        $kernel->pushMiddleware(\z5internet\ReactUserFramework\App\Http\Middleware\RefreshToken::class);

        $this->registerCommands();

        $this->app['config']->set('auth.guards.web.driver', 'rufJST');

        $this->app['auth']->extend('rufJST',function($app, $name, array $config) {

            return new RUFGuard($app, $this->app['auth']->createUserProvider($config['provider']));

        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom($this->configPath(), 'react-user-framework');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $key = 'jwt';

        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge($config, ['required_claims' => ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti', 'abc']]));

        $this->registerAddons();

        App::register(\Tymon\JWTAuth\Providers\LaravelServiceProvider::class);

        App::register(\Intervention\Image\ImageServiceProvider::class);

        App::register(\GrahamCampbell\Flysystem\FlysystemServiceProvider::class);

        $router = $this->app['router'];
        $method = method_exists($router, 'aliasMiddleware') ? 'aliasMiddleware' : 'middleware';
        $router->$method('auth', \z5internet\ReactUserFramework\App\Http\Middleware\Authenticate::class);

    }

    private function registerAddons() {

        foreach(addOns::getAddons() as $key => $value) {

//            App::register($value['providerClass']);

        }

    }

}