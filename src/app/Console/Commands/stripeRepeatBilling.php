<?php

namespace z5internet\ReactUserFramework\app\Console\Commands;

use Illuminate\Console\Command;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\StripeController;

class stripeRepeatBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-user-framework:stripeRepeatBilling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Stripe repeat billing';

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

    	(new StripeController)->runStripeRepeatBilling();

    }

}
