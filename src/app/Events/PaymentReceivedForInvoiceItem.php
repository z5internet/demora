<?php namespace z5internet\ReactUserFramework\App\Events;

use z5internet\ReactUserFramework\App\InvoiceDetail;

use Illuminate\Queue\SerializesModels;

class PaymentReceivedForInvoiceItem {

    use SerializesModels;

    public $invoiceDetail;

    public function __construct(InvoiceDetail $invoiceDetail) {

        $this->invoiceDetail = $invoiceDetail;

    }

}