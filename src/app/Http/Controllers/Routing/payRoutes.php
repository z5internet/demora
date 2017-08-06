<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Routing;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\StripeController;

use Illuminate\Http\Request;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\PayController;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\Team;

class payRoutes extends Controller {

	public function saveStripeToken(Request $request) {

		$teamId = (new Team)->currentTeam();

		$saveToken = (new StripeController)->saveStripeToken($teamId, $request->input('token'));

		return ['data' => []];

	}

	public function addProductToTeam($productId) {

		$teamId = (new Team)->currentTeam();

		$add = (new PayController)->addProductToTeam($teamId, $productId);

		return ['data' => ['result' => $add]];

	}

	public function processStripeEvent(Request $request) {

		$payload = json_decode($request->instance()->getContent());

		$payload = $payload->data->object;

		(new StripeController)->processEvent($payload);

	}

}
