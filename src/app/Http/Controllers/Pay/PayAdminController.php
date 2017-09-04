<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Pay;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\SubscribedPlans;

class PayAdminController extends Controller {

	public function getAllTeamsWithCurrentProductSubscription($productId) {

		$db = SubscribedPlans::where('product_id', $productId);

		$db = $db->where('ends_at', '>', app('db')->raw('now()'));
		$db = $db->where('invoice', '>', 0);

		return $db->get();

	}

}
