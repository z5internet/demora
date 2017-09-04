<?php namespace z5internet\ReactUserFramework\Providers;

use Illuminate\Console\Scheduling\Schedule;

use z5internet\ReactUserFramework\App\Http\Controllers\AddRouteController;

use z5internet\ReactUserFramework\App\Http\Controllers\Broadcast\LivePusherBroadcaster;

use Illuminate\Support\Arr;

trait ServiceProviderHelpers {

    private function configPath()
    {

        return __DIR__.'/../installationFiles/config/react-user-framework.php';

    }

    private $route;

    private function addRoutes() {

        $this->route = new AddRouteController($this->app);

        require __DIR__.'/../routes/web.php';
        require __DIR__.'/../routes/data.php';
        require __DIR__.'/../routes/mobile.php';
        require __DIR__.'/../routes/channels.php';

    }

    private function registerCommands() {

        $this->commands([

            \z5internet\ReactUserFramework\App\Console\Commands\build::class,
            \z5internet\ReactUserFramework\App\Console\Commands\server::class,
            \z5internet\ReactUserFramework\App\Console\Commands\install::class,
            \z5internet\ReactUserFramework\App\Console\Commands\stripeRepeatBilling::class,
            \z5internet\ReactUserFramework\App\Console\Commands\createRepeatInvoiceDetail::class,

        ]);

        $schedule = $this->app->make(Schedule::class);
        $schedule->command('react-user-framework:deleteFromPush')->everyMinute();
        $schedule->command('react-user-framework:stripeRepeatBilling')->dailyAt('12:00');

    }

    private function extendBroadcast() {

        $this->app[\Illuminate\Contracts\Broadcasting\Factory::class]->extend('livePusher', function ($app, array $config) {

            return new LivePusherBroadcaster(
                new \Pusher\Pusher($config['key'], $config['secret'],
                $config['app_id'], Arr::get($config, 'options', []))
            );

        });

    }

}