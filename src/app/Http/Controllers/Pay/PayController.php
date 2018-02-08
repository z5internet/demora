<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Pay;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\SubscribedPlans;

use z5internet\ReactUserFramework\App\PaymentDetails;

use z5internet\ReactUserFramework\App\Invoice;

use z5internet\ReactUserFramework\App\InvoiceDetail;

use z5internet\ReactUserFramework\App\Products;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\TeamsController;

use stdClass;

use z5internet\ReactUserFramework\App\Events\PaymentReceivedForInvoiceItem;

use Carbon\Carbon;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Events\AddedTeamMemberToPlan;

class PayController extends Controller {

	private $products = [];

	public function saveSubscriptionDetails($args) {

		$pd = PaymentDetails::firstOrNew(['team_id' => $args->team_id]);

		$pd->processor = $args->processor;
		$pd->subscription_id = $args->subscription_id;

		$pd->save();

		return $pd;

	}

	public function addProductToTeam($teamId, $productId) {

		$rolesAllowed = config('react-user-framework.pay.roles_allowed_to_purchase');

		$uid = app('auth')->id();

		$roleOfUser = (new TeamsController)->getRoleForUserInTeam($uid);

		if (!in_array($roleOfUser, $rolesAllowed)) {

			throw new \Exception('User '.$uid.' cannot add Product '.$productId.' to team '.$teamId);

		}

		if ($this->isTeamSubscribedToProductId($teamId, $productId)) {

			return 'alreadySubscribed';

		}

		$product = $this->getProductFromProductId($productId);

		if (!$product) {

			return "No product";

		}

		$getNextPriceTermForProduct = $this->getNextPriceTermForProduct($product, 1);

		$payment_details = $this->getPaymentInfo($teamId);

		if ($getNextPriceTermForProduct->amount==0 && (!$getNextPriceTermForProduct->trial_period_card_required || $payment_details)) {

			return $this->addFreeProductToTeam($teamId, $product, $getNextPriceTermForProduct);

		}

		if (!$payment_details) {

			return 'requiresPaymentInfo';

		}

		$result = false;

		$ends = date('Y-m-d H:i:s', strtotime('+ '.$getNextPriceTermForProduct->term));
		$now = date('Y-m-d H:i:s');

		$invdet = new stdClass;

		$invdet->team_id = $teamId;
		$invdet->quantity = 1;
		$invdet->description = $product->description;
		$invdet->unit_amount = $getNextPriceTermForProduct->amount;
		$invdet->product_id = $product->product_id;
		$invdet->tax = $product->tax;
		$invdet->currency = $product->currency;
		$invdet->date_to_process = '1970-01-01 00:00:00';
		$invdet->notes = json_encode(['product' => $product->product_id]);

		$invdet->period_from = $now;
		$invdet->period_to = $ends;

		$paymentForDetailLines = $product->auto_bill_for_extra_users?$this->addInvoiceDetail($this->getInvoiceDetailForUsers($product, $teamId, $now, $ends)):[];

		array_unshift($paymentForDetailLines, $this->addInvoiceDetail($invdet)[0]);

		$termPriceOfProduct = new stdClass;

		$termPriceOfProduct->term = '0 day';

		$this->addUpateSubscriptionPlan($teamId, $product, $termPriceOfProduct);

		if ($payment_details->processor == 'Stripe') {

			$total = $invdet->unit_amount*$invdet->quantity;
			$total += round($total*$invdet->tax/100);

			$result = (new StripeController)->processPayment([

				'currency' => $product->currency,
				'amount' => $total,
				'team_id' => $teamId,

			], $paymentForDetailLines);

			if ($result->failure_message) {

				$this->paymentFailedRevertInvoices($paymentForDetailLines);

				$result = 'failed';

			}
			else
			{

				$result = 'successful';

			}

		}

		return $result;

	}

	public function getProducts() {

		$products = Products::whereNull('archived');

		$products = $products->get([

			'id',
			'product_id',
			'product_group',
			'description',
			'initial_payment_term',
			'initial_payment_amount',
			'initial_payment_quantity',
			'trial_period_card_required',
			'trial_period',
			'amount',
			'currency',
			'term',
			'is_recurring',
			'tax',
			'users_included',
			'amount_per_user',

		]);

		foreach ($products as $key => $v) {

			$products[$key]->amount_per_user = json_decode($products[$key]->amount_per_user, 1);

		}

		return $products;

	}

	public function getProductFromId($id) {

		return Products::where('id', $id)->first();

	}

	public function getProductFromProductId($productId) {

		if (!isset($this->products[$productId])) {

			$this->products[$productId] = Products::where('product_id', $productId)->first();

		}

		return $this->products[$productId];

	}

	public function teamSubscribedTo($teamId, $cols=['*']) {

		$db = SubscribedPlans::where('team_id', $teamId);
		$db = $db->where('ends_at', '>', app('db')->raw('now()'));
		$db = $db->get($cols);

		$out = ['products' => new stdClass, 'groups' => new stdClass];

		foreach($db as $tdb) {

			$tdb->amount_per_user = json_decode($tdb->amount_per_user, 1);

			$product_id = $tdb->product_id;

			$product_group = $tdb->product_group;

			$out['products']->$product_id = $tdb;

			if ($product_group) {

				$out['groups']->$product_group = $tdb;

			}

		}

		return $out;

	}

	public function isTeamSubscribedToProductId($teamId, $productId) {

		$db = SubscribedPlans::where('product_id', $productId);

		$db = $db->where('team_id', $teamId);
		$db = $db->where('ends_at', '>', app('db')->raw('now()'));

		return !!$db->first();

	}

	private function isTeamSubscribedToProductGroup($teamId ,$productGroup) {

		$db = SubscribedPlans::where('product_group', $productGroup);

		$db = $db->where('team_id', $teamId);
		$db = $db->where('ends_at', '>', app('db')->raw('now()'));

		return !!$db->first();

	}

	private function getPaymentInfo($teamId) {

		$db = PaymentDetails::where('team_id', $teamId);

		return $db->first(['id', 'processor', 'subscription_id']);

	}

	public function recordPayment(Invoice $invoice, $paymentForDetailLines) {

		$check = Invoice::where('transaction_id', $invoice->transaction_id);

		$check = $check->where('processor', $invoice->processor)->first();

		if ($check) {

			return;

		}

		$invoice->save();

		if (!is_array($paymentForDetailLines)) {

			$paymentForDetailLines = [$paymentForDetailLines];

		}

		foreach ($paymentForDetailLines as $tp) {

			$invdet = InvoiceDetail::find($tp);

			$invdet->invoice_id = $invoice->id;

			$invdet->save();

			$invdet->notes = json_decode($invdet->notes, 1);

			foreach(array_keys($invdet->notes) as $action) {

				if ($action=='product') {

					$this->updateEndDateForSubscription($invdet->team_id, $invdet->notes['product'], $invdet->period_to);

				}

			}

			event(new PaymentReceivedForInvoiceItem($invdet));

		}

		return $invoice;

	}

	private function addUpateSubscriptionPlan($teamId, $product, $termPriceOfProduct) {

		if ($product->product_group) {

			$db = SubscribedPlans::firstOrNew(['team_id' => $teamId, 'product_group' => $product->product_group]);

		}
		else
		{

			$db = SubscribedPlans::firstOrNew(['team_id' => $teamId, 'product_id' => $product->product_id]);

		}

		$db->team_id = $teamId;
		$db->product_id = $product->product_id;
		$db->product_group = $product->product_group;

		$db->ends_at = date("Y-m-d H:i:s", strtotime('+'.$termPriceOfProduct->term));
		$db->amount = $product->amount;
		$db->currency = $product->currency;
		$db->term = $product->term;
		$db->is_recurring = $product->is_recurring;

		$db->trial_period = !!$product->trial_period;

		$db->description = $product->description;

		$db->tax = $product->tax;

		if ($db->status <> 'CANCELLED') {

			$db->status = '';

		}

		$db->initial_payment_amount = $product->initial_payment_amount;
		$db->initial_payment_quantity = $product->initial_payment_quantity;
		$db->initial_payment_term = $product->initial_payment_term;

		$db->amount_per_user = $product->amount_per_user;
		$db->users_included = $product->users_included;

		$db->auto_bill_for_extra_users = $product->auto_bill_for_extra_users;

		if (!is_string($db->amount_per_user)) {

			$db->amount_per_user = json_encode($db->amount_per_user);

		}

		$db->save();

		return ['id' => $db->id];

	}

	private function updateEndDateForSubscription($teamId, $product_id, $ends) {

		$db = SubscribedPlans::where(['team_id' => $teamId, 'product_id' => $product_id]);

		$db = $db->update(['ends_at' => $ends]);

		return $db;


	}

	public function updateProduct($productId, $data) {

		$product = Products::where('product_id', $productId)->first();

		foreach(array_keys($data) as $td) {

			$product->$td = $data[$td];

		}

		$product->save($data);

		return $product;

	}

	private function addFreeProductToTeam($teamId, $product, $termPriceOfProduct) {

		$ends = date('Y-m-d H:i:s', strtotime($termPriceOfProduct->term));

		$now = date('Y-m-d H:i:s');

		$invdet = new stdClass;

		$invdet->team_id = $teamId;
		$invdet->quantity = 1;
		$invdet->description = $product->description;
		$invdet->unit_amount = 0;
		$invdet->product_id = $product->product_id;
		$invdet->tax = $product->tax;
		$invdet->currency = $product->currency;
		$invdet->date_to_process = '1970-01-01 00:00:00';
		$invdet->notes = json_encode(['product' => $product->product_id]);

		$invdet->period_from = $now;
		$invdet->period_to = $ends;

		$paymentForDetailLines = $this->addInvoiceDetail($invdet);

		$invoice = new Invoice;

		$invoice->team_id = $teamId;
		$invoice->transaction_id = $teamId.'-'.time();

		$invoice->total = 0;
		$invoice->currency = strtoupper($product->currency);

		$invoice->converted_total = 0;
		$invoice->converted_fee = 0;
		$invoice->converted_currency = $product->currency;

		$invoice->card_country = '';
		$invoice->billing_state = '';
		$invoice->billing_zip = '';
		$invoice->billing_country = '';
		$invoice->processor = '';

		$invoice->tax = 0;
		$invoice->converted_tax = 0;

		$payment = $this->recordPayment($invoice, $paymentForDetailLines);

		$this->addUpateSubscriptionPlan($teamId, $product, $termPriceOfProduct);

		return 'successful';

	}

	public function getListOfInvoiceDetailToProcessForTodayAndPrevious5Days() {

		$out = [];

		$invdet = InvoiceDetail::where('date_to_process', '>=', app('db')->raw('subdate(now(), interval 5 day)'));

		$invdet = $invdet->whereNull('invoice_id')->get();

		foreach ($invdet as $idet) {

			if (!isset($out[$idet->team_id])) {

				$out[$idet->team_id] = [];

			}

			array_push($out[$idet->team_id], $idet);

		}

		return $out;

	}

	public function createProduct($data) {

		$product_id = $data['product_id'];

		$check_product_exists = Products::where('product_id', $product_id)->first();

		if ($check_product_exists) {

			return ['error' => 'This product ID '.$product_id.' is already being used'];

		}

		$product = new Products;

		return $this->saveProductToDB($product, $data);

	}

	public function editProduct($product_id, $data) {

		$product = Products::where('product_id', $data['product_id'])->first();

		return $this->saveProductToDB($product, $data);

	}

	public function saveProductToDB($product, $data) {

		$product->amount = $data['amount'];
		$product->currency = $data['currency'];
		$product->tax = $data['tax'];
		$product->term = $data['term'];

		$product->product_id = $data['product_id'];

		if ($data['product_group']) {

			$product->product_group = $data['product_group'];

		}

		$product->description = $data['description'];

		$data = $data + [
			'initial_payment_amount' => '',
			'initial_payment_term' => '',
			'initial_payment_quantity' => '',
			'trial_period_card_required' => '',
			'trial_period' => '',
			'users_included' => '',
			'amount_per_user' => '',
			'auto_bill_for_extra_users' => '',

		];

		if (
			$data['initial_payment_amount'] ||
			$data['initial_payment_term'] ||
			$data['initial_payment_quantity']
			) {

			if (!($data['initial_payment_amount'] &&
				$data['initial_payment_term'] &&
				$data['initial_payment_quantity']
				)) {

				return ['error' => 'To set an initial payment you must specify initial price, term and quantity'];

			}

		}

		if (
			$data['trial_period_card_required'] ||
			$data['trial_period']
			) {

			if (!($data['trial_period']
				)) {

				return ['error' => 'To must specify a trial period'];

			}

		}

		$product->is_recurring = $data['is_recurring']?$data['is_recurring']:0;

		$product->initial_payment_term = $data['initial_payment_term']?$data['initial_payment_term']:0;
		$product->initial_payment_amount = $data['initial_payment_amount']?$data['initial_payment_amount']:0;
		$product->initial_payment_quantity = $data['initial_payment_quantity']?$data['initial_payment_quantity']:0;

		$product->trial_period_card_required = $data['trial_period_card_required']?$data['trial_period_card_required']:0;
		$product->trial_period = $data['trial_period']?$data['trial_period']:0;

		$product->users_included = $data['users_included']?$data['users_included']:0;
		$product->amount_per_user = $data['amount_per_user']?json_encode($data['amount_per_user']):'{}';
		$product->auto_bill_for_extra_users = $data['auto_bill_for_extra_users']?$data['auto_bill_for_extra_users']:1;

		$product->save();

		return $product;

	}

	private function getNextPriceTermForProduct($product, $payment_number) {

		$return = new stdClass;
		$return->trial_period_card_required = 1;

		if ($product->trial_period && $payment_number == 1) {

			$return->term = $product->trial_period;
			$return->amount = 0;
			$return->trial_period_card_required = $product->trial_period_card_required;

			return $return;

		}

		if ($product->initial_payment_term && ($payment_number <= ($product->initial_payment_quantity+$product->trial_period))) {

			$return->term = $product->initial_payment_term;
			$return->amount = $product->initial_payment_amount;

			return $return;

		}

		$return->term = $product->term;
		$return->amount = $product->amount;

		return $return;

	}

	public function addInvoiceDetail($details) {

		if (!is_array($details)) {

			$details = [$details];

		}

		$ret = [];

		foreach ($details as $td) {

			$invdet = new InvoiceDetail;

			$invdet->invoice_id = null;
			$invdet->team_id = $td->team_id;
			$invdet->quantity = $td->quantity;
			$invdet->description = $td->description;
			$invdet->unit_amount = $td->unit_amount;
			$invdet->product_id = $td->product_id;
			$invdet->total = $td->unit_amount*$td->quantity;
			$invdet->tax_rate = $td->tax;
			$invdet->tax = round($invdet->total*$td->tax/100);
			$invdet->currency = $td->currency;
			$invdet->date_to_process = $td->date_to_process;
			$invdet->period_from = $td->period_from;
			$invdet->period_to = $td->period_to;
			$invdet->product_id = $td->product_id;
			$invdet->notes = $td->notes;

			$invdet->save();

			array_push($ret, $invdet->id);

		}

		return $ret;

	}

	public function createRepeatInvoiceDetail($provider) {

		$db = SubscribedPlans::where('is_recurring', 1);
		$db = $db->where(app('db')->raw('date(ends_at)'), app('db')->raw('date(now())'))->get();

		foreach($db as $product) {

			$pinfo = $this->getPaymentInfo($product->team_id);

			if (!$pinfo || $pinfo->processor <> $provider) {

				continue;

			}

			$numberInvoices = InvoiceDetail::where('product_id', $product->product_id);
			$numberInvoices = $numberInvoices->whereNull('invoice_id')->count();

			if ($numberInvoices > 0) {

				continue;

			}

			$numberInvoices = InvoiceDetail::where('product_id', $product->product_id);
			$numberInvoices = $numberInvoices->whereNotNull('invoice_id')->count();

			$getNextPriceTermForProduct = $this->getNextPriceTermForProduct($product, $numberInvoices+1);

			$ends = date('Y-m-d H:i:s', strtotime($getNextPriceTermForProduct->term));

			$now = date('Y-m-d H:i:s');

			$invdet = new stdClass;

			$invdet->team_id = $product->team_id;
			$invdet->quantity = 1;
			$invdet->description = $product->description;
			$invdet->unit_amount = $getNextPriceTermForProduct->amount;
			$invdet->product_id = $product->product_id;
			$invdet->tax = $product->tax;
			$invdet->currency = $product->currency;
			$invdet->date_to_process = date('Y-m-d');
			$invdet->notes = json_encode(['product' => $product->product_id]);

			$invdet->period_from = $now;
			$invdet->period_to = $ends;

			$paymentForDetailLines = $this->addInvoiceDetail($this->getInvoiceDetailForUsers($product, $product->team_id, $now, $ends));

			array_unshift($paymentForDetailLines, $this->addInvoiceDetail($invdet));

			$paymentForDetailLines = $this->addInvoiceDetail($invdet);

		}

	}

	public function addTeamMember($tid, $uid, $role) {

		$db = SubscribedPlans::where('team_id', $tid);
		$db = $db->where('ends_at', '>', app('db')->raw('now()'))->get([
			'auto_bill_for_extra_users',
			'product_id',
		]);

		foreach ($db as $td) {

			if ($td->auto_bill_for_extra_users) {

				$this->chargeForUser($tid, $uid, $td->product_id);

			}

			event(new AddedTeamMemberToPlan($tid, $uid, $td->product_id));

		}

	}

	public function chargeForUser($tid, $uid, $product_id) {

		$product = SubscribedPlans::where('team_id', $tid);
		$product = $product->where('product_id', $product_id)->first();

		$full_period = new Carbon(date('Y-m-d H:i:s', strtotime($product->term)));
		$ends_at = new Carbon($product->ends_at);

		$now = new Carbon();

		$days_to_bill = $now->diffInDays($ends_at);
		$full_period = $now->diffInDays($full_period);

		$ends_at = $product->ends_at;

		$price_for_extra_user = $this->getAmountForExtraUser($product);

		if ($product->is_recurring) {

			$price = intval($price_for_extra_user*$days_to_bill/$full_period);

		}
		else
		{

			$price = $price_for_extra_user;

		}

		if ($price == 0) {
			return;
		}

		$now = date('Y-m-d H:i:s');

		$invdet = new stdClass;

		$invdet->team_id = $tid;
		$invdet->quantity = 1;

		$name = UserController::user($uid);

		$invdet->description = 'Additional user ('.$name->first_name.' '.$name->last_name.') for '.$product->description;
		$invdet->unit_amount = $price;
		$invdet->product_id = $product->product_id;
		$invdet->tax = $product->tax;
		$invdet->currency = $product->currency;
		$invdet->date_to_process = '1970-01-01 00:00:00';
		$invdet->notes = json_encode(['users' => [$uid]]);

		$invdet->period_from = $now;
		$invdet->period_to = $ends_at;

		$paymentForDetailLines = $this->addInvoiceDetail($invdet);

		$payment_details = $this->getPaymentInfo($tid);

		if ($payment_details->processor == 'Stripe') {

			$result = (new StripeController)->processPayment([

				'currency' => $product->currency,
				'amount' => $price,
				'team_id' => $tid,

			], $paymentForDetailLines);

			if ($result['failure_message']) {

				$this->paymentFailedRevertInvoices($paymentForDetailLines);

			}

		}

		return $result;

	}

	public function getAmountForExtraUser($product) {

		$number_current_users = count((new TeamsController)->getTeamMembers($product->team_id)['active']);

		if (is_string($product->amount_per_user)) {

			$product->amount_per_user = json_decode($product->amount_per_user, 1);

		}

		$allowance_of_users = $product->users_included;

		if ($number_current_users < $allowance_of_users) {

			return 0;

		}

		$apu = $product->amount_per_user;

		$steps = array_keys($apu);

		sort($steps);

		foreach ($steps as $ts) {

			if ($ts == 0) {
				continue;
			}

			if ($number_current_users < $ts) {

				return $apu[$ts];

			}

		}

		return $apu[0];

	}

	public function paymentFailedRevertInvoices($ids) {

		foreach($ids as $tid) {

			InvoiceDetail::where('id', $tid)->whereNull('invoice_id')->delete();

		}

	}

	private function getInvoiceDetailForUsers($product, $teamId, $now, $ends) {

		$u = $this->getTotalAmountForUsers($product, $teamId);

		$iv = [];

		foreach($u as $tu) {

			$invdet = new stdClass;

			$invdet->team_id = $teamId;
			$invdet->quantity = $tu['number'];
			$invdet->description = 'Users';
			$invdet->unit_amount = $tu['amount'];
			$invdet->product_id = $product->product_id;
			$invdet->tax = $product->tax;
			$invdet->currency = $product->currency;
			$invdet->date_to_process = date('Y-m-d');
			$invdet->notes = json_encode(['product' => $product->product_id]);

			$invdet->period_from = $now;
			$invdet->period_to = $ends;

			array_push($iv, $invdet);

		}

		return $iv;

	}

	private function getTotalAmountForUsers($product, $teamId) {

		$number_current_users = count((new TeamsController)->getTeamMembers($teamId)['active']);

		if (is_string($product->amount_per_user)) {

			$product->amount_per_user = json_decode($product->amount_per_user, 1);

		}

		$apu = $product->amount_per_user;

		$steps = array_keys($apu);

		sort($steps);

		$return = [];

		$last = $product->users_included;

		foreach ($steps as $ts) {

			if ($ts == 0) {
				continue;
			}

			$a = $ts - $last;
			$b = $number_current_users - $last;

			if ($b < 1) {

				break;

			}

			$c = min($a, $b);

			array_push($return, [
				'amount' => $product->amount_per_user[$ts],
				'number' => $c,
			]);

			$last = $ts;

		}

		if ($number_current_users > $last) {

			array_push($return, [
				'amount' => $product->amount_per_user[0],
				'number' => $number_current_users - $last,
			]);

		}

		return $return;

	}

	public function willAppAutoChargeForUsers($subscription) {

		$abfeu = $subscription->auto_bill_for_extra_users;

		$ui = $subscription->users_included;

		if ($abfeu && $ui) {

			return $subscription->amount_per_user;

		}

		return false;

	}

}
