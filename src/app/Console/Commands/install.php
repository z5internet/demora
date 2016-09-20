<?php

namespace darrenmerrett\ReactUserFramework\app\Console\Commands;

use Illuminate\Console\Command;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-user-framework:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install react-user-framework resources, setup npm dependancies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $scripts = collect([
            \darrenmerrett\ReactUserFramework\installationFiles\scripts\migrations::class,
            \darrenmerrett\ReactUserFramework\installationFiles\scripts\models::class,
            \darrenmerrett\ReactUserFramework\installationFiles\scripts\controllers::class,
        ]);

        $scripts->each(function ($installer) { (new $installer($this))->install(); });

        $this->comment('Installation complete');

    }

}
