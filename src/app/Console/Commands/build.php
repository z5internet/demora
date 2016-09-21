<?php

namespace darrenmerrett\ReactUserFramework\app\Console\Commands;

use Illuminate\Console\Command;

class build extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-user-framework:build {--production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build react-user-framework';

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

        $args = $this->option('production')?' --production':'';
        
        system("NODE_ENV=production webpack --config ./vendor/darrenmerrett/ruf/src/webpack.config.js");

        print "\n";

    }

}
