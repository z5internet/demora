<?php namespace z5internet\ReactUserFramework\Providers;

use Illuminate\Console\Scheduling\Schedule;

use z5internet\ReactUserFramework\App\Http\Controllers\AddRouteController;

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

    }

    private function registerCommands() {

        $this->commands([

            \z5internet\ReactUserFramework\app\Console\Commands\build::class,
            \z5internet\ReactUserFramework\app\Console\Commands\server::class,
            \z5internet\ReactUserFramework\app\Console\Commands\install::class,
            \z5internet\ReactUserFramework\app\Console\Commands\deleteFromPush::class,
            \z5internet\ReactUserFramework\app\Console\Commands\stripeRepeatBilling::class,
            \z5internet\ReactUserFramework\app\Console\Commands\createRepeatInvoiceDetail::class,

        ]);

        $schedule = $this->app->make(Schedule::class);
        $schedule->command('react-user-framework:deleteFromPush')->everyMinute();
        $schedule->command('react-user-framework:stripeRepeatBilling')->dailyAt('12:00');

    }

}