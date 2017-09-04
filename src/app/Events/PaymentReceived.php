<?php namespace z5internet\ReactUserFramework\App\Events;

use z5internet\ReactUserFramework\App\Invoice;

use Illuminate\Queue\SerializesModels;

class PaymentReceived {

    use SerializesModels;

    public $payment;

    public function __construct(Invoice $payment) {

        $this->payment = $payment;

    }

}