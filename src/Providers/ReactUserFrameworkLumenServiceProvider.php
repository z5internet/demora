<?php namespace z5internet\ReactUserFramework\Providers;

use Illuminate\Support\ServiceProvider;

use App;

use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Request;

use z5internet\ReactUserFramework\App\Http\Controllers\Auth\AuthenticationController;

use z5internet\ReactUserFramework\addOns;

use z5internet\ReactUserFramework\App\Http\Controllers\Auth\RUFGuard;

class ReactUserFrameworkLumenServiceProvider extends ServiceProvider
{

    use ServiceProviderHelpers;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        app('db')->connection()->getSchemaBuilder()->defaultStringLength(191);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'react-user-framework');

        if (!class_exists('Pusher')) { // required for unit tests

            class_alias('Pusher\Pusher', 'Pusher');

        }

        $this->addRoutes();

        $this->registerMiddleware();

        $this->registerCommands();

        $this->loadMigrationsFrom(__DIR__.'/../installationFiles/database/migrations/');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        require __DIR__.'/../App/Http/Controllers/Helpers.php';

        $this->mergeConfigFrom($this->configPath(), 'react-user-framework');

        $this->app->configure('broadcasting');

        $this->app->configure('react-user-framework');

        $this->app->configure('mail');

        $this->app->configure('app');

        $this->extendBroadcast();

        $key = 'jwt';

        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge($config, ['required_claims' => ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti', 'abc']]));

        $this->registerAddons();

        $this->registerProviders();

    }

    private function registerProviders() {

        $this->app->register(\Intervention\Image\ImageServiceProvider::class);

        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);

        $this->app->register(\Illuminate\Mail\MailServiceProvider::class);

        $this->app->register(\GrahamCampbell\Flysystem\FlysystemServiceProvider::class);

        $this->app['auth']->viaRequest('data', function ($request) {

            $user = (new AuthenticationController($request))->checkAuthentication();

            return $user?$user:null;

        });

    }

    private function registerMiddleware() {

        $this->app->routeMiddleware(['auth' => \z5internet\ReactUserFramework\App\Http\Middleware\Authenticate::class]);

        $this->app->middleware(\z5internet\ReactUserFramework\App\Http\Middleware\AddRufParameterToJSONOutput::class);

        $this->app->middleware(\z5internet\ReactUserFramework\App\Http\Middleware\RefreshToken::class);

        $this->app->middleware(\z5internet\ReactUserFramework\App\Http\Middleware\RecordUserOnline::class);

        $this->app->routeMiddleware(['IsUserAWebsiteAdminMiddleware' => \z5internet\ReactUserFramework\App\Http\Middleware\IsUserAWebsiteAdminMiddleware::class]);

    }

    private function registerAddons() {

        foreach(addOns::getAddons() as $key => $value) {

//            App::register($value['providerClass']);

        }

    }

}
