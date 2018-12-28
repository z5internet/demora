<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Pay;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\PayController;

use stdClass;

use z5internet\ReactUserFramework\App\Invoice;

use Stripe\Stripe;

use Stripe\HttpClient\CurlClient;

use Stripe\ApiRequestor;

use Stripe\Customer;

use Stripe\Charge;

use Stripe\BalanceTransaction;

use z5internet\ReactUserFramework\App\PaymentDetails;

class StripeController extends Controller {

	private $stripe_config;

	public function __construct() {

		$stripe_config = config('react-user-framework.pay.stripe');

		$this->stripe_config = $stripe_config;

		Stripe::setApiKey($stripe_config['secret_key']);

		$curl = new CurlClient(array(CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2));
		ApiRequestor::setHttpClient($curl);

	}

	public function saveStripeToken($teamId, $token) {

		$customer = $this->createCustomerFromToken($token, $teamId);

		$pd = new stdClass;

		$pd->team_id = $teamId;
		$pd->processor = 'Stripe';
		$pd->subscription_id = $customer->id;

		return (new PayController)->saveSubscriptionDetails($pd);

	}

	public function createCustomerFromToken($token, $teamId) {

		$customer = Customer::create([

		  'source' => $token['id'],
		  'description' => $this->stripe_config['description'],
		  'metadata' => [
		  	'id' => $teamId,
		  ]

		]);

		return $customer;

	}

	public function processPayment($data, $invoice_detail_ids) {

		$token = PaymentDetails::where('team_id', $data['team_id'])
			->where('processor', 'Stripe')->first(['subscription_id'])->subscription_id;

		return $this->processStripePaymentWithToken($data, $invoice_detail_ids, $token);

	}

	public function processStripePaymentWithToken($data, $invoice_detail_ids, $token) {

		$charge = Charge::create([

		  'amount'   => $data['amount'],
		  'currency' => $data['currency'],
		  'customer' => $token,
		  'metadata' => [
		  	'teamId' => $data['team_id'],
		  	'invoice_detail_ids' => json_encode($invoice_detail_ids),
		  ],

		]);

		if (!$charge->failure_message) {

			$this->recordPayment($charge);

		}

		return $charge;

	}

	private function recordPayment($payload) {

        $payment = BalanceTransaction::retrieve($payload->balance_transaction);

		$invoice = new Invoice;

		$invoice->team_id = $payload->metadata->teamId;
		$invoice->transaction_id = $payment->id;

		$invoice->total = $payload->amount;
		$invoice->currency = strtoupper($payload->currency);

		$invoice->converted_total = $payment->amount;
		$invoice->converted_fee = $payment->fee;
		$invoice->converted_currency = strtoupper($payment->currency);

		$source = $payload->source;

		$invoice->card_country = $source->country;
		$invoice->billing_state = $source->address_state;
		$invoice->billing_zip = $source->address_zip;
		$invoice->billing_country = $source->address_country;
		$invoice->processor = 'Stripe';

		$invoice->tax = 0;
		$invoice->converted_tax = 0;

		return (new PayController)->recordPayment($invoice, json_decode($payload->metadata->invoice_detail_ids, 1));

	}

	public function processEvent($payload) {

		if ($payload->object <> 'charge') {

			return false;

		}

		Stripe::setApiKey($this->stripe_config['secret_key']);

		if (isset($payload->balance_transaction)) {

			$payment = $this->recordPayment($payload);

			return true;

		}
		else
		{

			return false;

		}

	}

	public function runStripeRepeatBilling() {

		$invoicedetail = (new PayController)->getListOfInvoiceDetailToProcessForTodayAndPrevious5Days();

		foreach (array_keys($invoicedetail) as $team_id) {

			$amount = [];

			foreach ($invoicedetail[$team_id] as $ti) {

				if (!isset($amount[$ti->currency])) {

					$amount[$ti->currency] = [
						'amount' => 0,
						'ids' => [],
					];

				}

				$amount[$ti->currency]['amount'] += $ti->total + $ti->tax;

				array_push($amount[$ti->currency]['ids'], $ti->id);

			}

			foreach (array_keys($amount) as $currency) {

				$this->processPayment([

					'currency' => $currency,
					'amount' => $amount[$currency]['amount'],
					'team_id' => $team_id,

				], $amount[$currency]['ids']);

			}

		}

	}

}
