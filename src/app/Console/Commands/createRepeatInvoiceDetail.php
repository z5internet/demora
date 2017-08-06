<?php

namespace z5internet\ReactUserFramework\app\Console\Commands;

use Illuminate\Console\Command;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\StripeController;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\PayController;

class createRepeatInvoiceDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-user-framework:createRepeatInvoiceDetail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repeat invoice detail';

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

        if (config('react-user-framework.pay.create_invoice_detail_cron')) {

    	   (new PayController)->createRepeatInvoiceDetail('Stripe');

        }

    }

}
